<?php
// [moved to glow-fundraiser-core] require_once get_template_directory() . '/inc/fundraiser-docx.php';

global $etheme_theme_data;
$etheme_theme_data = wp_get_theme( get_stylesheet_directory() . '/style.css' );
// [bridge: Royal framework not loaded under non-Royal theme] require_once( get_template_directory() . '/framework/init.php' );
// require_once( get_template_directory() . '/shortcode/shortcode.php' );
// require_once( get_template_directory() . '/shortcode/shortcode-new.php' );
// require_once( get_template_directory() . '/admin-panel/fundraiser_operation.php' );
// Moved to plugin: glow-post-types (Glow Custom Post Types)
// require_once( get_template_directory() . '/admin-panel/post-types.php' );
// require_once( get_template_directory() . '/rebooking/rebooking-post-ajax.php' );
// require_once( get_template_directory() . '/rebooking/rebookbutton_shortcode.php' );

// require_once( get_template_directory() . '/admin-panel/send_check_orders.php' );
// require_once( get_template_directory() . '/admin-panel/search_fundrasier_ajax.php' );
// require_once( get_template_directory() . '/email/fundraiser_email_automation.php' );
// require_once( get_template_directory() . '/admin-panel/fundraiser_email_tracking.php' );
// require_once( get_template_directory() . '/admin-panel/fundrasier-booking-detail.php' );
// require_once( get_template_directory() . '/admin-panel/theme_fundraiser_feedbacks.php' );
// require_once( get_template_directory() . '/admin-panel/wp_paid_fundraiser_ajax_record.php' );
// require_once( get_template_directory() . '/admin-panel/wp_unpaid_fundraiser_ajax_record.php' );
// require_once( get_template_directory() . '/admin-panel/wp_send_check_order_ajax_record.php' );
// require_once( get_template_directory() . '/admin-panel/wp_outstanding_fund_ajax.php' );
// require_once( get_template_directory() . '/admin-panel/fundraiser-outstanding.php' );

// require_once( get_template_directory() . '/admin-panel/ajax_send_check_received_email.php' );
// require_once( get_template_directory() . '/admin-panel/create_merge_order_by_cron_job.php' );

// require_once( get_template_directory() . '/representative-admin-panel/representative-operation.php' );

/////
/* custome code starts here
wp_enqueue_style( 'style', get_template_directory_uri() . '/css/custom_css.css');

/* proxima-nova font*/
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'proxima-nova-font', get_stylesheet_directory_uri() . '/fonts/proxima-nova/fonts.css');

	// Localize the script with new data
	wp_register_script( 'ajax-js', get_stylesheet_directory_uri() . '/js/custom_js.js', array( 'jquery' ), 1.1, true );
	wp_localize_script( 'ajax-js', 'ajax_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'site_url' => site_url(), 'theme_url' => get_stylesheet_directory_uri() ) );
	wp_enqueue_script( 'ajax-js' );
} );


function wpdocs_scripts_method() {
	wp_localize_script( 'ajax-script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_scripts_method' ); 





/*
* Creating a function to add custom settings for ACF
*/
	add_action('acf/render_field_settings', 'my_admin_only_render_field_settings');

	function my_admin_only_render_field_settings( $field ) {

		acf_render_field_setting( $field, array(
			'label'			=> __('Hide Label ?'),
			'instructions'	=> '',
			'name'			=> 'hide_label',
			'type'			=> 'true_false',
			'ui'			=> 1,
		), true);

		acf_render_field_setting( $field, array(
			'label'			=> __('Hide Field ?'),
			'instructions'	=> '',
			'name'			=> 'hide_field',
			'type'			=> 'true_false',
			'ui'			=> 1,
		), true);
	}

/*
* Creating a function to use custom settings for ACF
*/
	add_filter('acf/prepare_field', 'my_admin_only_prepare_field');

	function my_admin_only_prepare_field( $field ) {

		// if 'hide_label' setting
		if( isset($field['hide_label']) && $field['hide_label'] == true)
		{
			$field["wrapper"]["class"]="hide_label_for_this";	// add class to wrapper to hide label
		}

		// if 'hide_field' setting
		if( isset($field['hide_field']) && $field['hide_field'] == true)
		{
			if(!is_admin())
			{
				$field["wrapper"]["class"]="hide_this_wrapper";	// add class to wrapper to hide wrapper

				$six_digit_unique_number = get_unique_number();
				$field["value"]=$six_digit_unique_number; // set unique 6 digits fundraiser ID
			}
		}

		// return
		return $field;
	}


/*
* Creating a function to send email after fundraiser signup from ACF
*/
	function my_acf_save_post( $post_id )
	{
		// set post status as draft
		$created_post = array( 'ID' => $post_id, 'post_status' => "draft" );
		wp_update_post($created_post);

		// add code to send email
		send_mail_about_fundraiser();
		// echo $post_id;
		// echo "send email to ".$_POST['acf']['field_5a9a2fa423958'];

		//$value = get_field('my_field');
	}

	add_action('acf/save_post', 'my_acf_save_post', 20);


/*
* Creating a function to generate a unique number
*/
	function send_mail_about_fundraiser()
	{
		// echo "in function";
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$fundraiser_id = $_POST['acf']['field_5a9a90c16db95']?$_POST['acf']['field_5a9a90c16db95']:"";
		$org_name = $_POST['acf']['field_5a9a20622394c']?$_POST['acf']['field_5a9a20622394c']:"";
		$dir_name = $_POST['acf']['field_5a9a2bd52394d']?$_POST['acf']['field_5a9a2bd52394d']:"";
		$prev_fundraiser = $_POST['acf']['field_5a9a2c0d2394e']?$_POST['acf']['field_5a9a2c0d2394e']:"";
		$rep_name = $_POST['acf']['field_5a9a2d132394f']?$_POST['acf']['field_5a9a2d132394f']:"";
		$forgot_rep = $_POST['acf']['field_5a9a2d3623950']?$_POST['acf']['field_5a9a2d3623950']:"";
		$how_did_hear_arr = $_POST['acf']['field_5a9a2ded23952']?$_POST['acf']['field_5a9a2ded23952']:array();
		$how_did_hear = implode(", ",$how_did_hear_arr);
		$your_fvrt_cndls_arr = $_POST['acf']['field_5ac62daec134d']?$_POST['acf']['field_5ac62daec134d']:array();
		$your_fvrt_cndls = implode(", ",$your_fvrt_cndls_arr);
		$shipping_addr = $_POST['acf']['field_5a9a2e2323953']?$_POST['acf']['field_5a9a2e2323953']:"";
		$city = $_POST['acf']['field_5a9a2e3223954']?$_POST['acf']['field_5a9a2e3223954']:"";
		$state = $_POST['acf']['field_5a9a2e5323955']?$_POST['acf']['field_5a9a2e5323955']:"";
		$zip = $_POST['acf']['field_5a9a2f7123956']?$_POST['acf']['field_5a9a2f7123956']:"";
		$phone_num = $_POST['acf']['field_5a9a2f8d23957']?$_POST['acf']['field_5a9a2f8d23957']:"";
		$fundraiser_email = $_POST['acf']['field_5a9a2fa423958']?$_POST['acf']['field_5a9a2fa423958']:"";
		$num_participants = $_POST['acf']['field_5a9a306d59874']?$_POST['acf']['field_5a9a306d59874']:"";
		$start_date = $_POST['acf']['field_5a9a307959875']?$_POST['acf']['field_5a9a307959875']:"";
		$end_date = $_POST['acf']['field_5a9a308b59876']?$_POST['acf']['field_5a9a308b59876']:"";
		$org_profit_check = $_POST['acf']['field_5a9a30a359877']?$_POST['acf']['field_5a9a30a359877']:"";
		$fundraiser_goal_amnt = $_POST['acf']['field_5a9a30e259878']?$_POST['acf']['field_5a9a30e259878']:"";
		$funds_go_toward = $_POST['acf']['field_5a9a30f959879']?$_POST['acf']['field_5a9a30f959879']:"";
		$goal_of_items = $_POST['acf']['field_5a9a31085987a']?$_POST['acf']['field_5a9a31085987a']:"";
		$checks_payable_to = $_POST['acf']['field_5a9a31395987b']?$_POST['acf']['field_5a9a31395987b']:"";


		$mail_body = "Your Fundraiser ID: ".$fundraiser_id."<br><br><br><br>
		Organization's Name: ".$org_name." <br>
		Director's Name: ".$dir_name." <br>
		Previous Fundraiser?: ".$prev_fundraiser." <br>
		Rep's Name?: ".$rep_name." <br>
		Forgot Rep's Name?: ".$forgot_rep." <br>
		How did you hear about Glow?: ".$how_did_hear." <br>
		What are your favorite type of candle scents?: ".$your_fvrt_cndls." <br>
		Shipping Address: ".$shipping_addr." <br>
		City: ".$city." <br>
		State: ".$state." <br>
		Zip: ".$zip." <br>
		Phone Number: ".$phone_num." <br>
		Email: ".$fundraiser_email." <br>
		Number of Participants: ".$num_participants." <br>
		Start Date: ".$start_date." <br>
		End Date: ".$end_date." <br>
		Organization Profit Check: ".$org_profit_check." <br>
		Fundraising Goal Amount: ".$fundraiser_goal_amnt." <br>
		Funds Go Toward: ".$funds_go_toward." <br>
		Goal of #items to sell: ".$goal_of_items." <br>
		Checks made payable to: ".$checks_payable_to;

		// die;
		//send mail();
	}


/*
* Creating a function to generate a unique number
*/
	function get_unique_number()
	{
		$six_digit_random_number = mt_rand(100000, 999999);
		//check if it is a unique number
		$check_existing = array(
			'post_type' => 'fundraiser',
			'meta_query' => array(
				array(
					'key' => 'fundraiser_id',
					'value' => $six_digit_random_number
				)
			),
		);
		$existing_posts = get_posts($check_existing);

		if ( !$existing_posts )
		{
			return $six_digit_random_number;
		}
		else
		{
			/*foreach ( $existing_posts as $existing_post ) :
				setup_postdata( $existing_post );
				get_the_ID();
			endforeach;
			wp_reset_postdata();
			*/
			get_unique_number();
		}
	}

	add_action('acf/save_post', 'my_acf_save_post', 20);


/*
* Creating a function to allow user to add valid fundraiser ID to get discount
*/
	add_action('wp_ajax_nopriv_wp_fundraiser_id_validitor', 'wp_fundraiser_id_validitor'); // for not logged in users
	add_action('wp_ajax_wp_fundraiser_id_validitor', 'wp_fundraiser_id_validitor');
	function wp_fundraiser_id_validitor()
	{
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }
		$fundraiser_id = $_REQUEST['fund_id']?$_REQUEST['fund_id']:"";

		$check_existing = array(
			'post_type' => 'fundraiser',
			'meta_query' => array(
				array(
					'key' => 'fundraiser_id',
					'value' => $fundraiser_id
				)
			),
		);

		$existing_posts = new WP_Query( $check_existing );
		// echo "<pre>";
		// print_r($existing_posts);
		// echo "</pre>";
		if($existing_posts->have_posts())
		{
			$_SESSION['fundraiser_set']=false;
			unset($_SESSION['fundraiser_id']);
			unset($_SESSION['fundraiser_post_id']);
			unset($_SESSION['fundraiser_org_name']);

			while ( $existing_posts->have_posts() )
			{

				$existing_posts->the_post();
				get_the_ID();

				$_SESSION['fundraiser_set']=true;
				$_SESSION['fundraiser_id']=$fundraiser_id;
				$_SESSION['fundraiser_post_id']=get_the_ID();
				$_SESSION['fundraiser_org_name']=get_field('organizations_name',get_the_ID());
			}
			echo $_SESSION['fundraiser_org_name'];
		}
		else
		{
			echo "fail";
		}
		die;
	}


/*
* Creating a function to allow user to add valid fundraiser ID to get discount
*/
	add_action('wp_ajax_nopriv_wp_unset_fundraiser_id', 'wp_unset_fundraiser_id'); // for not logged in users
	add_action('wp_ajax_wp_unset_fundraiser_id', 'wp_unset_fundraiser_id');
	function wp_unset_fundraiser_id()
	{
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }

		$_SESSION['fundraiser_set']=false;
		unset($_SESSION['fundraiser_id']);
		unset($_SESSION['fundraiser_post_id']);
		unset($_SESSION['fundraiser_org_name']);
	}


/*
* Creating a function to attach fundraiser ID to successfull orders
*/
	add_action('woocommerce_checkout_order_processed', 'custom_process_order', 10, 1);
	function custom_process_order($order_id) {
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }

		$order = new WC_Order( $order_id );

		if($_SESSION['fundraiser_set'])
		{
			update_post_meta($order_id, 'order_fundraiser_id', $_SESSION['fundraiser_id']);
			update_post_meta($order_id, 'order_fundraised_at_percent', '40');
		}
		return $order_id;
	}


/*
* Creating a function to add shortcode for social icons
*/
	function ls_social_icon_func( $atts ) {
		return '<ul class="menu-social-icons ">
				<li class="firstItem">
					<a href="https://www.facebook.com/GLOW-Scented-Candles-133915660003970/" class="title-toolip tooltipstered" target="_blank">
						<i class="ico-facebook"></i>
						<svg width="38" height="38" xmlns="http://www.w3.org/2000/svg" class="circle">
							<circle cx="19" cy="19" r="18" fill="rgba(255,255,255,0)" stroke="#000000"></circle>
						</svg>
					</a>
				</li>
	        </ul>';
	}
	add_shortcode( 'ls_social_icon', 'ls_social_icon_func' );


function create_user_from_join_us($cfdata) {
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {

        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } else {
        // We can't retrieve the form data
        return $cfdata;
    }
    // Check this is the user registration form
    if ( $cfdata->title() == 'Join Us') {
        $checkbox = isset( $formdata['accept'] );
        if ( $checkbox ) {
            $password = wp_generate_password( 12, false );
            $email = $formdata['email'];
            $name = $formdata['your-name'];
            // Construct a username from the user's name
            $username = strtolower(str_replace(' ', '', $name));
            $name_parts = explode(' ',$name);
            if ( !email_exists( $email ) ) {
                // Find an unused username
                $username_tocheck = $username;
                $i = 1;
                while ( username_exists( $username_tocheck ) ) {
                    $username_tocheck = $username . $i++;
                }
                $username = $username_tocheck;
                // Create the user
                $userdata = array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_email' => $email,
                    'nickname' => reset($name_parts),
                    'display_name' => $name,
                    'first_name' => reset($name_parts),
                    'last_name' => end($name_parts),
                    'role' => 'subscriber'
                );
                $user_id = wp_insert_user( $userdata );
                if ( !is_wp_error($user_id) ) {
                    // Email login details to user
                    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                    $message = "Welcome to GLOW SCENTED CANDLES! <br><br>Your login details are as follows:" . "<br><br>";
                    $message .= sprintf(__('Username: %s'), $username) . "<br><br>";
                    $message .= sprintf(__('Password: %s'), $password) . "<br><br>";
                    $message .= wp_login_url() . "<br><br>";

					$subject = 'Your username and password';

					$headers = array('Content-Type: text/html; charset=UTF-8');
 
					wp_mail( $email, $subject, $message, $headers );

                    //wp_mail($email, sprintf(__('[%s] Your username and password'), $blogname), $message);
                }
            }
        } else {
            //disable registration
        }
    }
    return $cfdata; 
}
 
add_action('wpcf7_before_send_mail', 'create_user_from_join_us', 1);

add_filter( 'woocommerce_states', 'custom_us_states', 10, 1 );
function custom_us_states( $states ) {
    $non_allowed_us_states = array( 'AK', 'HI'); 

    // Loop through your non allowed us states and remove them
    foreach( $non_allowed_us_states as $state_code ) {
        if( isset($states['US'][$state_code]) )
            unset( $states['US'][$state_code] );
    }
    return $states;
}


/**
 * Function that will check for user role and turn off VAT/tax for that role
 */
function wc_diff_rate_for_user() {

	// check for the user role
	if ( is_user_logged_in() && (current_user_can( 'fundraiser' ) || current_user_can( 'wholesaler' ) || current_user_can( 'sales' )) ) {

		// set the customer object to have no VAT
		WC()->customer->is_vat_exempt = true;
	}

}
add_action( 'template_redirect', 'wc_diff_rate_for_user', 1 );

/**
 * Function that filters the variable product hash based on user
 */
function wc_get_variation_prices_hash_filter( $hash, $item, $display ) {

	// check for the user role
	if ( is_user_logged_in() && (current_user_can( 'fundraiser' ) || current_user_can( 'wholesaler' ) || current_user_can( 'sales' )) ) {

		// clear key 2, which is where taxes are
		$hash['2'] = array();
	}

	// return the hash
	return $hash;
}
add_filter( 'woocommerce_get_variation_prices_hash', 'wc_get_variation_prices_hash_filter', 1, 3 );

/**
 * Function that removes the price suffix (inc. Tax) from variable products based on role
 */
function wc_get_price_suffix_filter( $price_display_suffix, $item ) {

	// check for the user role
	if ( is_user_logged_in() && (current_user_can( 'fundraiser' ) || current_user_can( 'wholesaler' ) || current_user_can( 'sales' ))) {

		// return blank if it matches
		return '';
	}

	// return if unmatched
	return $price_display_suffix;
}
add_filter( 'woocommerce_get_price_suffix', 'wc_get_price_suffix_filter', 10, 2 );

/*
* Creating a function to send email to seller register 
*/
	add_action('wp_ajax_nopriv_wp_seller_send_email', 'wp_seller_send_email'); // for not logged in users
	add_action('wp_ajax_wp_seller_send_email', 'wp_seller_send_email');
	function wp_seller_send_email( )
	{
		$to = $_POST['email'];
		$fid = strval($_POST['f_id']);
		//$register_link = 'https://glowbrandscandles.com/seller-register/?frid='.$f_id;
		//$link= "<a href=".$register_link.">Seller Registration</a>";
		$subject = "Fundraiser Invite! You To Register As Seller. ";
		$body ='';
	ob_start();
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 700px;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Seller Registration Invitation </h1>
		  	</td>
		</tr>
		 <tr>
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
								<tr>
									<td align="left">
										<p style="font:17px/23px  PTSansRegular , Arial, Helvetica, sans-serif;color:#1b0f0e;">Hi </p>
										<p  style="font:17px/23px PTSansRegular, Arial, Helvetica, sans-serif;color:#1b0f0e;">Fundraiser invite you to register as seller. Click here <?php echo ('<a href="' . glow_dynamic_url_base('home') . 'seller-register/?frid=').$_POST['f_id'].'" target="_blank">Seller Register Form</a>';  ?> </p>
											<p  style="font:17px/23px PTSansRegular, Arial, Helvetica, sans-serif;color:#1b0f0e;">Fundraiser ID: <?php echo $_POST['f_id']; ?> </p>
										<p style="font:17px/23px  PTSansRegular , Arial, Helvetica, sans-serif;color:#1b0f0e;">Thanks! </p>
								</td>
			
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
		/*$headers = "From: Glow Scented Candles <info@glowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	if($mailSuc){
		echo "Your message has been successfully sent.";
	} else{
		echo "Error!!";
	}
	die();
		
	
		
	}
	?>
	<?php
	/*
* Creating a function to send email to Fundraiser Report 
*/
	add_action('wp_ajax_nopriv_custom_fund_report_mail', 'custom_fund_report_mail'); // for not logged in users
	add_action('wp_ajax_custom_fund_report_mail', 'custom_fund_report_mail');
	function custom_fund_report_mail( )
	{
		$ID = isset( $_POST['fund_id'] ) ? absint( $_POST['fund_id'] ) : 0;
		$user_info = get_userdata($ID);
		if ( ! $user_info || ! is_email( $user_info->user_email ) ) {
			wp_die( 'Invalid fundraiser or email address.', '', array( 'response' => 400 ) );
		}
		// Empty/manual-seller reports must also work on PHP 8.
		$seller_name = array();
		$total_sale_so_far = $rebate_so_far = 0;
		$total_sale_so_far2 = $rebate_so_far2 = 0;
		$user_email = $user_info->user_email;
		
		 $to = $user_email; 
	/*	$to = "shivanideveloper1991@gmail.com"; */
		$subject = "Fundraiser Report!!";
		$body ='';
	ob_start();
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Fundraiser Report <?php echo "#".$ID; ?> </h1>
		  	</td>
		</tr>
			<?php
				$u_id=$ID;
			   $f=get_user_meta( $u_id , 'first_name' , true );		
			   $l=get_user_meta( $u_id , 'last_name' , true );								   
			 //  $org=get_user_meta( $u_id , 'user_registration_input_company' , true );
			   $org=get_user_meta( $u_id , 'user_registration_name_of_school_business' , true );
			   $sd=get_user_meta( $u_id , 'user_registration_start_date' , true );
			   $ed=get_user_meta( $u_id , 'user_registration_end_date' , true );
			   $referral=get_user_meta( $u_id , 'user_registration_referral_name' , true );
			   $Participants= get_user_meta( $u_id , 'user_registration_participants_no' , true );
			   $goal_amount=get_user_meta( $u_id , 'user_registration_fr_goal' , true );
			   $go_toward=get_user_meta( $u_id , 'user_registration_fund_destination' , true );  
			   $selling_goal=get_user_meta( $u_id , 'user_registration_selling_goal' , true );
			   $payable=get_user_meta( $u_id , 'user_registration_check_payable' , true );
				/* Rebate Total */
			   $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {  
                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
								  }
								} 
								 else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									  $Discount = number_format($Discount, 2, '.', '');
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								 }
								} 
								else{}
								} }
                            /*     } */
						}
						
						 /* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						 // End Ship to school
						
                        ?>
		 <tr>
             <td align="left" style="padding: 30px 30px;">
			 <div class="text-left" style="float:left;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Glow Scented Candles:</strong><br>
							3702 Cavalier Drive Suite 100<br>
							Garland TX 75042<br>
							<?php echo esc_url( glow_dynamic_url_base('home') ); ?><br>
							contact@glowscentedcandles.com<br>
							(972) 941-6874
				</address>
			</div>
			<div class="text-right" style="float:right;text-align:right;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong><?php echo $org; ?></strong><br>
							<?php echo $f; ?> <?php echo $l; ?><br>
				</address>
			</div>
			</td>
		</tr>
		 <tr>
             <td align="left" style="padding: 0px 30px;">
			 <div class="text-left" style="float:left;">
			
			  <?php 
			 //Ship to School
			 $total_sale_fundraiser = number_format($total_sale_so_far, 2, '.', ''); 
			 $total_rebate_fundraiser = number_format($rebate_so_far, 2, '.', '');
			
			 $total_sale_fundraiser2 = number_format($total_sale_so_far2, 2, '.', ''); 
			 $total_rebate_fundraiser2 = number_format($rebate_so_far2, 2, '.', '');
			
			 $total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
			
			 $total_sale_fundraiser3 = number_format($total_sale_fundraiser3, 2, '.', '');
			
			 $total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
			
			 $total_rebate_fundraiser3 = number_format($total_rebate_fundraiser3, 2, '.', '');
			 
			 //End Ship to school
			 ?>
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Period:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br>
							<strong style="color: green;">Total Sale:</strong> <span style="color: green;"><?php echo "$".$total_sale_fundraiser3;?></span><br>
							<strong style="color: green;">Total Rebate:</strong><span style="color: green;"><?php echo "$".$total_rebate_fundraiser3; ?></span><br>
							<strong style="color: #333;font-size: 14px;">Note: A+B+C+D+F = Total Rebate and Total Sale.</strong><br>
				</address>
			</div>
			<div class="text-right" style="float:right;text-align:right;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Org Profit Check:</strong> <?php echo $org; ?><br>
							<strong>Fundraising Goal:</strong> <?php echo $goal_amount; ?><br>
							<strong>Funds Go Toward: </strong> <?php echo $go_toward; ?><br>
							<strong>Selling Goal (# of Items):</strong> <?php echo $selling_goal; ?><br>	
							<strong>Checks Made Payable To:</strong> <?php echo $payable; ?><br>
						</address>
			</div>
			</td>
		</tr>
	
		<!--<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border:0;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>-->
		 <?php
		 /* Start Print All Seller order List */
                              
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
								
					$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) 
								  { 
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } }
						}
						 }
					?>
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
								<?php
					}
								 $arr=array();
								 foreach ( $all_meta_for_users as $all_meta_for_user ) { 
								 $arr[]=$all_meta_for_user->ID;
								 }
         foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border:0;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							    $item_qty=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								 $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
									/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data(); 
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else if($order_type=="") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else{}
																								
                                        endforeach; 
										
									/* total Item  Sold */
								  if ($frID3 != $order_customer_id) { 
                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										  $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }
									  else
									  {
										$total_sale_so_far+=$order->get_subtotal();
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                     $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							} } }
                                  ?>
								  <?php  } 
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');  ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                     $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  }  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php } 
              /* End Print All Seller order List */        ?>
			  <?php /* Start Print Manually Add Seller order List */ ?>
			<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border:0;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
                            if ($ID3 == "custom") { 

                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										  $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }
									  else
									  {
										$total_sale_so_far+=$order->get_subtotal();
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                     $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                  ?>
						<?php  } } } }
						}
						
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									} }
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count($seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
							$item_qty_manual=0;
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														 foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type=="Online") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else if($order_type=="") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else{}
														endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									} }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						$item_qty_arr[]=$item_qty_manual;
						}
						
						
						
						
						
						
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Seller</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');  ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							<?php
								$exist_seller=count($seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
							 <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count($seller_name);$i++)
							{ ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $seller_t=number_format($seller_total_Sale_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $seller_r=number_format($seller_rebate_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
						         <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_arr[$i]; ?></span></strong></td>
								</tr>
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) {
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php }  } } } }
                        }
							}
                        ?>
							  
						</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>	  
			<?php /* End Print Manually Add Seller order List */ ?>				  
			
		 <?php /* Start Print Seller not mentioned order List */ ?>
			<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border:0;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_not_found=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
									 $wc_is_mergeOrder = get_post_meta($order->get_id(), 'wc_seller_total_mergeOrder', true );
									 if(empty($wc_is_mergeOrder)) 
									 {  
                            if (empty($ID3)) { 

                             $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										  $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }
									  else
									  {
										$total_sale_so_far+=$order->get_subtotal();
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }
                                      foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty_not_found +=  $item_data['quantity']; 
											}
											else if($order_type=="") 
											{
												$item_qty_not_found +=  $item_data['quantity']; 
											}
											else{}
                                    endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                  $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                  ?>
								  <?php  }  } } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: Seller not mentioned</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');  ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_not_found; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
									 $wc_is_mergeOrder = get_post_meta($order->get_id(), 'wc_seller_total_mergeOrder', true );
									 if(empty($wc_is_mergeOrder)) 
									 {  
                            if (empty($ID3)) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = "N/A";
                                        /*$slr_info  = get_userdata($slrID);*/
                                       ?>
                                       <?php echo $slrID; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								  <?php  }  } } } }
                        }
                        ?>
							  
						</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>	  
			<?php /* End Print Seller not mentioned order List */ ?>		
			<!-- Fundraiser Online Sales-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                     foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="Online") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else if($order_type_2=="") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
						} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing')) {
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
			
			
		 <!-- Catalog Sale -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  
							   $total_cat_quantity=0;
							  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  $new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										
                                     $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
											$item_id   = $item_values->get_id();
											$item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 
											$total_cat_quantity += $item_data['quantity']; 			//ship to school												
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
										

								 }
							
							} }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: Fundraiser Catalogue Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th>
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							   $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                              
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							     if ($order_type=='Catlog') { 
								   $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=number_format( $total_sale, 2); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS || !empty($new_catalog_order) && $new_catalog_order == "Yes")
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>	
		
			<!-- Online Ship to School -->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();

									/* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                
                               
							  
						} } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far;
							$rebate_so_far = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far;
                            $rebate_so_far = number_format($Discount, 2, '.', '');
							
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#ff5722;">F: Ship to School Orders</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Rebate</th> -->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($frID3 != $order_customer_id)  
								{
									if ($scat==1) {
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='ship-to-school') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"> <?php 
                                     $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									  
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							 <?php  } } } }
                        }
                        ?>
			 
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
		/*$headers = "From: Glow Scented Candles <info@glowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	if($mailSuc){
		echo "Your report has been successfully sent.";
	} else{
		echo "Error!!";
	}
	die();
		
	
		
	}
	 /* Email to registered user */ 
	function send_welcome_email_to_new_user($user_id) {
    $user = get_userdata($user_id);
	
	if(in_array('representative', (array) $user->roles )){
		
	}
	else
	{
		$user_email = $user->user_email;
		$character = json_decode(stripslashes(isset($_POST['form_data']) ? (string)$_POST['form_data'] : ''));	
		// echo "<pre>";
		// print_r($character);		       		      
		// die;  
		$recipient="candace@glowscentedcandles.com"; 
		$recipient2="";  
	//	$recipient="jatinarora0075@gmail.com";
		$referral_email=$character[5]->value;
		$org_label_check=$character[0]->label;
		$custom_form = $character[28]->value;
		if($org_label_check=="Organization Name")
		{ 
			$check="fund";
			$multiple_recipients = array(
					
					$recipient,
					$recipient2,
					
				);
		}
		elseif($custom_form=="Custom_new_form")
		{
			$check="fund";
			$multiple_recipients = array(
					
					$recipient,
					$recipient2,
					
				);
				
		}
		else
		{
			$recipient_main=$recipient;
		}
		 $user_full_name =  $character[1]->value.' '.$character[2]->value;
		 /*$org=get_user_meta($user_id, "user_registration_input_company", true);*/
		 $org=$character[0]->value;
		 $org_label=$character[0]->label;
		 $sales_label=$character[4]->label;
		 $custom_form = $character[28]->value;
		 $seller_register_page_identify=$character[5]->label;
		 $seller_register_check=0;
		  if($seller_register_page_identify=="Seller Goal: (Dollar Amount)"){
				$seller_register_check=1;
		  }
		 // Now we are ready to build our welcome email
		 $to = $user_email;
		 if($org_label=="Organization Name"){ 
			//$subject = "Fund Raising Campaign";
			// $subject = "Glow Fundraiser Registered Booked"; 
			$subject = "Your GLOW fundraiser has been registered!";
		 }
		 elseif($sales_label=="Shipping Address: (required)"){
			  $subject = "Sales Campaign";
		 }
		 elseif($custom_form=="Custom_new_form")
		 {
			 
			//$subject = "Fund Raising Registered";
			//$subject = "Glow Fundraiser Registered Booked"; 
			$subject = "Your GLOW fundraiser has been registered!";
			/* QR Code */
			require_once( GLOW_FC_DIR . 'includes/phpqrcode/qrlib.php' );
			$text = ("" . glow_dynamic_url_base('home') . "profile/").$user_id;
			$arrayUploadPath = wp_upload_dir();
			$fileUploadSubDir = $arrayUploadPath['basedir']."/qrcodes/image_".$user_id.".png";
			QRcode::png($text, $fileUploadSubDir);
			$fileUpload_full_link = $arrayUploadPath['baseurl']."/qrcodes/image_".$user_id.".png";
			if(!empty($fileUpload_full_link)) {
				update_user_meta($user_id,'fundraiser_barcode_link',$fileUpload_full_link);
			}
			/* Register link for dashboard */
			
		/*	$text2 = "https://glowbrandscandles.com/seller-register/?frid=".$user_id;
			$arrayUploadPath2 = wp_upload_dir();
			$fileUploadSubDir2 = $arrayUploadPath2['basedir']."/qrcodes/image_seller_register".$user_id.".png";
			QRcode::png($text2, $fileUploadSubDir2);
			$fileUpload_full_link2 = $arrayUploadPath2['baseurl']."/qrcodes/image_seller_register".$user_id.".png";
			*/
			
			$text2 = ("" . glow_dynamic_url_base('home') . "seller-register/?frid=") . $user_id;

			$arrayUploadPath2 = wp_upload_dir();
			$tempFile = $arrayUploadPath2['basedir'] . "/qrcodes/temp_" . $user_id . ".png";
			$fileUploadSubDir2 = $arrayUploadPath2['basedir'] . "/qrcodes/image_seller_register" . $user_id . ".png";

			// 1. Generate QR code to temp file
			QRcode::png($text2, $tempFile, QR_ECLEVEL_L, 3, 4);

			// 2. Resize to 111x111 px with GD
			$src = imagecreatefrompng($tempFile);
			$dst = imagecreatetruecolor(111, 111);
			imagecopyresized($dst, $src, 0, 0, 0, 0, 111, 111, imagesx($src), imagesy($src));
			imagepng($dst, $fileUploadSubDir2);

			// 3. Cleanup
			imagedestroy($src);
			imagedestroy($dst);
			unlink($tempFile);
			
			$fileUpload_full_link2 = $arrayUploadPath2['baseurl']."/qrcodes/image_seller_register".$user_id.".png";
			if(!empty($fileUpload_full_link2)) {
				update_user_meta($user_id,'fundraiser_seller_register_barcode_link',$fileUpload_full_link2);
			}
			/* End */
			
		 }
		 else
		 {
			   $subject = "Seller Registered";
		 }
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
	   <?php
	   
	   if($custom_form=="Custom_new_form")
	   {
	    //Geeks
			$get_referral = $character[20]->value;
			$referralUser = get_user_by( 'email', $get_referral );        
			$referralUserID = $referralUser->data->ID;
			if(!empty($referralUserID)){
				$current_paymentPostID=get_user_meta($referralUserID, "um_unique_payment_id", true );
				update_user_meta($user_id,'referral_payment_postid',$current_paymentPostID);
				$exist_payids=get_post_meta( $current_paymentPostID, "fundraiser_ids", true );
				if(!empty($exist_payids))
				{
					$exist_payids_arr=explode(",",$exist_payids);
					if (!in_array($user_id,$exist_payids_arr)) {
							array_push($exist_payids_arr, $user_id);
							$exist_payids_arrNew=implode(",",$exist_payids_arr);
							update_post_meta($current_paymentPostID,'fundraiser_ids',$exist_payids_arrNew);
					} 
				}
				else if(empty($exist_payids)){
						update_post_meta($current_paymentPostID,'fundraiser_ids',$user_id);
				}
				else{
						
				}
				//End
			}
				
	    $user_full_name =  $character[9]->value.' '.$character[10]->value;     
	    $reason_for_fundraising = $character[24]->value;
	    $rmail = $character[20]->value;

$fund_id      = $user_id; 
$org_name     = $character[25]->value;
$reason = !empty($reason_for_fundraising) ? $reason_for_fundraising . "." : "";
$items_goal   = $character[23]->value;
$end_date     = $character[8]->value;
$chair_phone  = $character[12]->value;
$chair_name   = trim(($character[9]->value ?? '') . ' ' . ($character[10]->value ?? '')); // falls back to your $character map
//$chair_email  = $character[17]->value;
$chair_email = $character[16]->value ?? '';
$chair_phone = $character[11]->value ?? '';
$seller_url   = ('' . glow_dynamic_url_base('home') . 'seller-register/?frid=') . $fund_id;
// QR code for Seller Register (if generated)
$qr_path = WP_CONTENT_DIR . "/uploads/qrcodes/image_{$fund_id}.png";

$qr_url = WP_CONTENT_DIR . "/uploads/qrcodes/image_seller_register{$fund_id}.png";




$docx_path = build_fundraiser_docx_for_user([
    'fund_id'     => $fund_id,
    'org_name'    => $org_name,
    'reason'      => $reason,
    'items_goal'  => $items_goal,
    'end_date'    => $end_date,
    'chair_name'  => $chair_name,
    'chair_phone' => $chair_phone,
    'chair_email' => $chair_email,
    'seller_url'  => $seller_url,
    'qr_path'     => $qr_path,
    'qr_url'      => $qr_url, 
]);

$admin_email = array(
    "candace@glowscentedcandles.com",
    "candacethompson12@gmail.com"
);


$admin_message = '
<h3 style="font-size:18px;">🏫 Fundraising Agreement</h3>
<p><b>School/Group Name:</b> '.$character[0]->value.'<br>
📞 <b>Phone:</b> '.$character[1]->value.'<br>
📍 <b>Address:</b> '.$character[2]->value.', '.$character[3]->value.', '.$character[4]->value.' '.$character[5]->value.'<br>
👥 <b>Participants:</b> '.$character[6]->value.'<br>
📅 <b>Start Date:</b> '.$character[7]->value.'<br>
📅 <b>End Date:</b> '.$character[8]->value.'<br>
🛍️ <b>Fundraiser Type:</b> '.$character[29]->value.'</p>

<hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

<h2 style="font-size:18px;margin-top:25px;">👩‍💼 Chairperson Information</h2>
<p>
<b>Name:</b> '.$character[9]->value.' '.$character[10]->value.'<br>
📱 <b>Cell:</b> '.$character[11]->value.'<br>
🏠 <b>Address:</b> '.$character[12]->value.', '.$character[13]->value.', '.$character[14]->value.' '.$character[15]->value.'<br>
📧 <b>Email:</b> '.$character[16]->value.'<br>
🔑 <b>Password:</b> '.$character[17]->value.'<br>
📦 <b>Brochures Ship To:</b> '.$character[30]->value.'
</p>
';

$admin_headers = array(
    'Content-Type: text/html; charset=UTF-8',
    'From: ' . GLOW_FROM_EMAIL
);

$admin_subject = "New Fundraiser Registration – Admin Summary";

// SEND ONLY THIS EMAIL TO ADMIN
wp_mail($admin_email, $admin_subject, $admin_message, $admin_headers);
//wp_mail('hamzatechypros105@gmail.com', $admin_subject, $admin_message, $admin_headers);
	 ?>
	 <!-- mail Your GLOW Fundraiser Is Officially Registered! Save This Email for Details -->
	   
<div style="margin:0;padding:0;background:#f7f7f7;font-family:Arial,Helvetica,sans-serif;color:#333;width:100%;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width:900px;margin:30px auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    <!-- HEADER -->
    <tr>
      <td style="background:#333;padding:25px 30px;text-align:left;">
        <h1 style="color:#fff;font-size:26px;margin:0;font-weight:400;">🎉 Your Glow Fundraiser Has Been Registered!</h1>
      </td>
    </tr>

    <!-- BODY -->
    <tr>
      <td style="padding:30px;">
        <p>Hi <strong><?php echo $user_full_name; ?></strong> 👋</p>
        <p>✨ <b><em>Please do not delete this email — it contains your fundraiser details and login info!</em></b></p>
        <p>Thank you for booking your <b>We’re thrilled to work with you and your group to make your sale fun, profitable, successful, and stress-free. 🌙 <em>Please read the important details below and save this email for reference — it has everything you’ll need for a glowing success!</em> ✨</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <!-- PARTICIPANT LETTER -->
        <h2 style="color:#1f1f1f;font-size:18px;">💌 Participant Letter</h2>
        <p>📎 <em>Attached:</em> Your customizable <strong>Participant Letter</strong></p>
        <p>Please review and edit if needed — it contains all the details from your registration.</p>  
        <p>💡 Share it with your group to keep everyone informed and excited — it’s a great way to boost participation and sales!</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <!-- DIRECTOR LOGIN -->
        <h2 style="color:#1f1f1f;font-size:18px;margin-top:25px;">💻 Your Director Login</h2>
        <p>Manage everything for your fundraiser online:</p>
        <p><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>my-account/?email=<?php echo $character[16]->value; ?>&pass=<?php echo $character[17]->value; ?>" target="_blank" style="color:#4184f3;text-decoration:none;">🔗 Access Your Director Dashboard</a></p>

        <p style="margin-top: 15px;"><b>Login Details:</b><br>
        📧 Email: <?php echo $character[16]->value; ?><br>
        🔑 Password: <?php echo $character[17]->value; ?><br>
        🪪 Fundraiser ID: <?php echo $user_id; ?><br>
        🌐 Profile Link: <a href="<?php echo ('' . glow_dynamic_url_base('home') . 'profile/').$user_id.'?reason_is='.$reason_for_fundraising; ?>" target="_blank" style="color:#4184f3;"><?php echo ('' . glow_dynamic_url_base('home') . 'profile/').$user_id; ?></a></p>

        <p style="margin-top: 15px;"><b>Your Dashboard Lets You:</b></p>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type: none;">
          <li>✅ View real-time sales</li>
          <li>✅ Invite sellers to register & track their own progress</li>
          <li>✅ Place your catalog order at the end of your sale</li>
          <li>✅ Monitor your total fundraiser profits</li>
        </ul>

        <!-- QR CODES -->
        <h3 style="margin-top:25px;">📸 QR Codes</h3>
        <table width="100%" style="margin:10px 0;">
          <tr>
            <td width="50%" align="center">
              <?php if(!empty($fileUpload_full_link)) { ?>
                <img src="<?php echo $fileUpload_full_link; ?>" width="120" height="120" alt="Profile QR Code"/><br>
                <a href="<?php echo $fileUpload_full_link; ?>" style="color:#4184f3;text-decoration:none;" target="_blank">Profile QR Code</a>
              <?php } ?>
            </td>
            <td width="50%" align="center">
              <?php if(!empty($fileUpload_full_link2)) { ?>
                <img src="<?php echo $fileUpload_full_link2; ?>" width="120" height="120" alt="Seller Register QR Code"/><br>
                <a href="<?php echo $fileUpload_full_link2; ?>" style="color:#4184f3;text-decoration:none;" target="_blank">Seller Register QR Code</a>
              <?php } ?>
            </td>
          </tr>
        </table>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <!-- FUNDRAISING AGREEMENT -->
        <h3 style="font-size:18px;">🏫 Fundraising Agreement</h3>
        <p><b>School/Group Name:</b> <?php echo $character[0]->value; ?><br>
        📞 <b>Phone:</b> <?php echo $character[1]->value; ?><br>
        📍 <b>Address:</b> <?php echo $character[2]->value; ?>, <?php echo $character[3]->value; ?>, <?php echo $character[4]->value; ?> <?php echo $character[5]->value; ?><br>
        👥 <b>Participants:</b> <?php echo $character[6]->value; ?><br>
        📅 <b>Start Date:</b> <?php echo $character[7]->value; ?><br>
        📅 <b>End Date:</b> <?php echo $character[8]->value; ?><br>
    	🛍️ <b>Fundraiser Type:</b> <?php echo $character[29]->value; ?></p>

    	<hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <!-- CHAIRPERSON INFO -->
        <h2 style="font-size:18px;margin-top:25px;">👩‍💼 Chairperson Information</h2>
        <p>
          <b>Name:</b> <?php echo $character[9]->value; ?> <?php echo $character[10]->value; ?><br>
          📱 <b>Cell:</b> <?php echo $character[11]->value; ?><br>
          🏠 <b>Address:</b> <?php echo $character[12]->value; ?>, <?php echo $character[13]->value; ?>, <?php echo $character[14]->value; ?> <?php echo $character[15]->value; ?><br>
          📧 <b>Email:</b> <?php echo $character[16]->value; ?><br>
          🔑 <b>Password:</b> <?php echo $character[17]->value; ?><br>
          📦 <b>Brochures Ship To:</b> <?php echo $character[30]->value; ?>
        </p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <!-- FUNDRAISER DETAILS -->
        <h2 style="font-size:18px;margin-top:25px;">💛 Fundraiser Details</h2>
        <p>✨ <b>Returning Glow Fundraiser:</b> <?php echo $character[31]->value; ?><br>
        ✨ <b>How You Heard About Glow:</b> <?php echo $character[18]->value; ?><br>
        ✨ <b>Name of Referral/Representative/Website:</b> <?php echo $character[19]->value; ?><br>
        ✨ <b>Referral/ Your Representative’s Email Address:</b> <?php echo $character[20]->value; ?><br>
        ✨ <b>Favorite Candle Scents:</b> <?php echo $character[21]->value; ?><br>
        💬 <b>Fundraising Sales Goal:</b> <?php echo $character[24]->value; ?><br>
        💵 <b>Selling Goal (per participant):</b> <?php echo $character[25]->value; ?></p>

        <h2 style="font-size:16px;margin-top:25px;">💬 Reason for Fundraising:</h2>
        <p><?php echo $character[30]->value; ?> would like to help lower the costs of ice and competition fees.</p>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">💵 Profit Check Payable To: <?php echo $character[25]->value; ?></p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <!-- Brochures DETAILS -->
        <h2 style="font-size:18px;margin-top:25px;">📦 Brochures</h2>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">Your brochures will ship directly from <b>GLOW</b>, and you’ll receive an email with your tracking number once they’re on the way.</p>

        <h2 style="font-size:18px;margin-top:25px;">💻 Your Online Store Is Live — Let’s Glow!</h2>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;"><em>Be sure to <b>upload your logo and store message</b> to make it personal and inspiring!</em> 🌟</p>

        <h2 style="font-size:18px;margin-top:25px;">🔥 Online Sales</h2>
        <p>🌎 <b>Reach family & friends nationwide</b> — more reach = more sales!<br>
        💳 <b>Cash-free & convenient</b> — perfect for online shoppers.<br>
        💸 <b>Average order over $100</b> — watch your profits soar!<br>
        🛒 <b>Exclusive online deals</b> starting at just $10!<br>
        💛 <b>Every item sold helps your cause shine bright!</b></p>
        <p style="font-size:14px;line-height:1.6; margin-top: 25px;">👉 <b>Pro Tip:</b> The more you share, the more you sell — post your link everywhere and watch your glow grow! 🌙</p>
        <p style="font-size:14px;line-height:1.6; margin-top: 25px;"><b>📣 Share your fundraiser!</b></p>
        <p style="font-size:14px;line-height:1.6;">Send parents and supporters your <b><a href="<?php echo ('' . glow_dynamic_url_base('home') . 'profile/').$user_id.'?reason_is='.$reason_for_fundraising; ?>" target="_blank" style="color:#4184f3;"><?php echo ('' . glow_dynamic_url_base('home') . 'profile/').$user_id; ?></a></b> and <b><?php echo $user_id; ?></b> via email, text, or Facebook — and encourage them to “Shop & Support!”</p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">🌟 Profit Structure</h2>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type: disc;">
          <li>💰 50% Profit — when 150+ total items are sold (brochure + ship-to-group)</li>
          <li>💰 40% Profit — when fewer than 100 items are sold</li>
          <li>💻 Online Ship-to-Home Orders: 40% profit</li>
          <li>🕯️ Orders typically ship within 7 business days.</li>
        </ul>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">🚚 Shipping Info</h2>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type: disc;">
          <li>Brochure Orders: Flat $11.95 (Free for $99+ orders)</li>
          <li>Online Ship-to-Group: $1.95 processing fee</li>
          <li>Online Ship-to-Home: Flat $11.95 (Free for $99+ orders)</li>
        </ul>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">🧾 Seller Registration</h2>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">When your fundraiser begins, have all sellers register using the Participant Letter. <br/>📩 Remind them not to use school email addresses (filters can block messages).</p>
        <p style="font-size:14px;line-height:1.6; margin-top: 25px;">Once registered, each seller receives their:</p>
        <p style="font-size:14px;line-height:1.6;"><b>🔗 Personal Store Link</b> — to share via text, email, or social media</p>
        <p style="font-size:14px;line-height:1.6;"><b>📊 Seller Dashboard Link</b> — to track their sales and goals</p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">🧾 Seller Registration</h2>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">Encourage participation and make selling fun!</p>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">🎉 <b>Examples:</b></p>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type: disc;">
          <li>🍕 Pizza or 🍩 Donut Party for top-selling teams</li>
          <li>🍔 Lunch with the coach or teacher</li>
          <li>🎶 Music Day or Early Dismissal Pass</li>
          <li>🍦 Ice Cream or Movie Day for hitting group goals</li>
        </ul>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">💡 One group raised over $32,000 by offering logo water bottles and t-shirts!</p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">🕓 Closing Your Fundraiser</h2>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type: decimal;">
          <li>Remind sellers of your end date and collect brochures.</li>
          <li>Review order forms carefully</li>
          <li>Log in → click <b>“Order Now”</b> to submit sales.</li>
          <li>Double-check totals before submitting.</li>
          <li>❌ Don’t click “Close Fundraiser” until everything is finalized.</li>
          <li>Online profits automatically apply to your invoice.</li>
          <li>Late orders may be entered before final closure.</li>
        </ul>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">💳 Payment Options</h2>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">After submitting your order, you’ll receive your invoice via email.</p>
        <p style="font-size:14px;line-height:1.6; margin-top: 25px;">💳 <b>Credit Card:</b> Pay via Authorize Net</p>
        <p style="font-size:14px;line-height:1.6;">💌 <b>Check:</b> Payable to <em>Glow Brands LLC</em></p>
        <p style="font-size:14px;line-height:1.6;">🏫 <b>Purchase Order:</b> Schools can email POs to contact@glowscentedcandles.com</p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">📦 Shipping & Distribution</h2>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">All orders are packed by seller — no sorting needed! 🙌</p>
        <p style="font-size:14px;line-height:1.6;">📍 Shipped from Garland, TX within 7–10 business days, plus 3–5 days for UPS delivery.</p>
        <p style="font-size:14px;line-height:1.6;">📦 You’ll receive your tracking info by email and inside your dashboard.</p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <h2 style="font-size:18px;margin-top:25px;">📊 Reports & Replacements</h2>
        <ul style="margin:0 0 15px 18px;padding:0;list-style-type: decimal;">
          <li>Print your Final Sales Report in your dashboard (bottom right corner).</li>
          <li>Use it for prizes, missing item claims, or late orders.</li>
          <li>Sellers should check boxes immediately and report issues within <b>3 days.</b></li>
        </ul>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">🛠️ To report missing or damaged items, log in and click <b>“Report Missing/Damaged Items.”</b></p>

        <hr style="border:none;border-top:1px solid #eee;margin:25px 0;">

        <!-- SUPPORT -->
        <h2 style="font-size:18px;">💬 We’re Here for You</h2>
        <p style="font-size:14px;line-height:1.6; margin-top: 15px;">We’re always here to help — from kickoff to closeout! 💛</p>
        <p style="font-size:14px;line-height:1.6; margin-top: 25px;">📞 Need assistance or have questions?</p>
        <p>Reach out to your <b>Glow Sales Representative</b> directly — they’re here to help guide you every step of the way with tips, reminders, and support throughout your fundraiser.</p>
        <p style="font-size:14px;line-height:1.6; margin-top: 25px;">If you need additional help or general assistance:</p>
        <p> 📧<b> Email:</b> <a href="mailto:contact@glowscentedcandles.com" style="color:#4184f3;">contact@glowscentedcandles.com</a><br>
        🌐 <b>Website:</b><a href="<?php echo esc_url( glow_dynamic_url_base('root') ); ?>" style="color:#4184f3;">www.glowscentedcandles.com</a></p>
        <p style="font-size:14px;line-height:1.6; margin-top: 25px;">✨ Whether it’s tracking sales, ordering materials, or boosting participation — we’ve got your back every step of the way!</p>
      </td>
    </tr>
  </table>
</div>



<?php }
	  elseif($custom_form=="Custom_new_form1")
	  { 
			//Geeks
			$get_referral = $character[23]->value;
			$referralUser = get_user_by( 'email', $get_referral );        
			$referralUserID = $referralUser->data->ID;
			if(!empty($referralUserID)){
			$current_paymentPostID=get_user_meta($referralUserID, "um_unique_payment_id", true );
			update_user_meta($user_id,'referral_payment_postid',$current_paymentPostID);
			$exist_payids=get_post_meta( $current_paymentPostID, "fundraiser_ids", true );
			if(!empty($exist_payids))
			{
				$exist_payids_arr=explode(",",$exist_payids);
				if (!in_array($user_id,$exist_payids_arr)) {
						array_push($exist_payids_arr, $user_id);
						$exist_payids_arrNew=implode(",",$exist_payids_arr);
						update_post_meta($current_paymentPostID,'fundraiser_ids',$exist_payids_arrNew);
				} 
			}
			else if(empty($exist_payids)){
					update_post_meta($current_paymentPostID,'fundraiser_ids',$user_id);
			}
			else{
					
			}
			//End
			}
	  
	  ?>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px 20px;width: 920px;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Fundraiser Registered </h1>
		  	</td>
		</tr>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;float:left;">
           <td align="left" style="width: 875px;display: inline-block;">
			    <p>Hi <?php echo $user_full_name; ?></p>
			    <p style="color: red;"><b>!!PLEASE DO NOT DELETE THIS EMAIL!!</b></p>
				<p> Thank you for booking your GLOW SCENTED CANDLES fundraiser! We are excited to work with you & your group while making the sale as successful, hassle-free and turnkey as possible.</p>
				<p style="color: #000; text-decoration: underline;"><b>CATALOG SALES (if your group is doing online only, please disregard)</b></p>
				<p>Glow will ship your brochures along with kick off product to you within 2 weeks of your sale start date via UPS to the address given below. Tracking information will be provided once it ships. In the meantime, you can set your online account below.  </p>
			  
				<p style="color: #000; text-decoration: underline;"><b>ONLINE "DIRECTOR LOGIN":</b></p>
				<p>Click the following link <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>my-account/?email=<?php echo $user_email; ?>&pass=<?php echo $character[14]->value; ?>" target="_blank"><?php echo esc_url( glow_dynamic_url_base('home') ); ?>my-account</a> to access the Director Login. </p> 
				<p><b>Login Details: </b></p>
				 <p><b>Email Address: </b> <?php echo $user_email; ?> </p>
				 <p><b>Password: </b> <?php echo $character[14]->value; ?> </p>
				 
				<p>Here you can-</p>
				<ul>
					<li>View your up-to-date sales</li>
					<li>Invite sellers to register for accounts to track their own sales</li>
					<li>Place your catalog order at the end of your fundraiser</li>
					<li>View your total fundraiser profits</li>
				 </ul>
			
				<p><h4>FUNDRAISING AGREEMENT</h4></p>	
				
				<p><b>School/Group Name: </b><?php echo $character[0]->value; ?></p>
				<p><b>School /Group Phone Number : </b><?php echo $character[1]->value; ?></p>
				<p><b>School/Group Address : </b><?php echo $character[2]->value; ?></p>
				<p><b>City : </b><?php echo $character[3]->value; ?></p>
				<p><b>State  : </b><?php echo $character[4]->value; ?></p>
				<p><b>Zip Code  : </b><?php echo $character[5]->value; ?></p>
				<p><b>Select Fundraiser Type  : </b><?php echo $character[6]->value; ?></p>
				<p><b>Number of Participants  : </b><?php echo $character[7]->value; ?></p>
				<p><b>Date Sale Starts  : </b><?php echo $character[8]->value; ?></p>
				<p><b>Date Sale Ends  : </b><?php echo $character[9]->value; ?></p>
				
				<p><h4>CHAIRPERSON’S INFORMATION</h4></p>	
				
				<p><b>First Name: </b><?php echo $character[10]->value; ?></p>
				<p><b>Last Name : </b><?php echo $character[11]->value; ?></p>
				<p><b>Chairperson’s Cell Phone Number : </b><?php echo $character[12]->value; ?></p>
				<p><b>Chairperson’s Address : </b><?php echo $character[13]->value; ?></p>
				
				
				<p><b>City  : </b><?php echo $character[14]->value; ?></p>
				<p><b>State  : </b><?php echo $character[15]->value; ?></p>
				<p><b>Zip Code  : </b><?php echo $character[16]->value; ?></p>
				<p><b>Chairperson’s Email Address  : </b><?php echo $character[17]->value; ?></p>
				<p><b>Create a Chairperson’s Password  : </b><?php echo $character[18]->value; ?></p>
				<p><b>Brochures will be shipped to School/Group or to the Chairperson?  : </b><?php echo $character[19]->value; ?></p>
				
				<p><h4>DETAILS</h4></p>	
				
				<p><b>Have you had a GLOW Fundraiser before  : </b><?php echo $character[20]->value; ?></p>
				<p><b>How did you hear about GLOW : </b><?php echo $character[21]->value; ?></p>
				<p><b>Name of Referral/Representative/Website : </b><?php echo $character[22]->value; ?></p>
				<p><b>Referral/ Your Representative’s Email Address : </b><?php echo $character[23]->value; ?></p>
				<p><b>What are your favorite candle scents : </b><?php echo $character[24]->value; ?></p>
				<p><b>Fundraising Sales Goal (Dollar Amount Earned) : </b><?php echo $character[25]->value; ?></p>
				<p><b>Selling Goal (# of items to be sold per participant) : </b><?php echo $character[26]->value; ?></p>
				<p><b>Reason for Fundraising (A message to be read by your customers for why you are fundraising/how the funds raised will be used.) : </b><?php echo $character[27]->value; ?></p>
				<p><b>Profit Check for Online Store will be payable to : </b><?php echo $character[28]->value; ?></p>
				
				<ul>
					<li>Boost sales and reach everyone with or online shopping option. This is the perfect solution to out of town family & friends. Customer pays for their order online via credit card, their order drop ships directly to the customer within 7 days.</li>
					<li>Glow issues (ONE) profit check to the fundraiser director for easy disbursing of profits/funds earned by seller. YES, sales are tracked by sellers, do not worry!</li>
					<li>Online sales earn 40% profits, due to shipping and direct drop ship. For the highest profits, we encourage the catalog sale.</li>
					<li>Fundraising Directors will receive a report of profits earned on the 15th of the month concluding their sale. Check will be distributed to the group name listed on this form. If different, please notify Glow.</li>
				</ul>
				<p style="font-size: 22px; color: green;"><b>Please note: Customer(s) must enter the 6 digit FUNDRAISER ID CODE or use the full fundid link for sales to be tracked. (Box is found top left of the website)</b></p>
				<p>Want to send the parents an email, text or facebook announcing GLOW with the website? Simply copy and paste the website along with the FUNDRAISING CODE and remind them to "shop away"</p>
				<p><b>If you have any questions at all during your sale please feel free to contact us, we are here to help!</b></p>
				<p>The Glow Team<font color="#888888"><br>Glow Scented Candles<br>
				<a href="mailto:contact@glowscentedcandles.com" target="_blank">contact@glowscentedcandles.com</a><br>
				<a href="<?php echo esc_url( glow_dynamic_url_base('root') ); ?>" target="_blank">www.glowscentedcandles.com</a><u></u><u></u></font></p>
			  </td>
		</tr>
	</table>
</div>
 <?php
	}
	elseif($sales_label=="Shipping Address: (required)"){
?>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
		<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 100%;overflow: hidden;">
			<tr>
				 <td align="center" style="padding:30px; text-align:center;background:#333;">
					<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Join Us As Sales </h1>
				</td>
			</tr>
			 <tr style="padding: 25px; max-width: 100%;margin: 0 auto;float:left;">
			   <td align="left" style="display: inline-block;">
					<p>Hi <?php echo $character[1]->value; ?></p>
					<?php echo $emailArr2= get_option('name_option_you_want_sales'); ?>
				</td>
			</tr>
        </table>
</div>
<?php
	}
	else if($seller_register_check==1) {
?>
<!-- email Welcome to Your Glow Fundraiser -->
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f6f6f6;width:100%;padding:0;margin:0;">
  <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#ffffff" style="max-width:900px;margin:40px auto;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <tr>
      <td align="center" style="background:#333;padding:30px;">
        <h1 style="color:#ffffff;font-size:28px;font-weight:400;margin:0;text-align:center;">💛 Welcome to Your Glow Fundraiser, <?php echo $character[0]->value; ?>!</h1>
		  	</td>
		</tr>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;float:left;">
           <td align="left" style="width: 875px;display: inline-block;">
      <?php //echo $emailArr3= get_option('name_option_you_want_seller'); ?>
	  <?php $fID=$character[4]->value; 
	  //$org=get_user_meta($fID, "user_registration_input_company", true);
	  $org=get_user_meta($fID, "user_registration_name_of_school_business", true);
	  	$user = get_user_by('id', $user_id);
		if($user)
		{
			$username=$user->user_login;
		}
		/* QR Code */
			require_once( GLOW_FC_DIR . 'includes/phpqrcode/qrlib.php' );
			$text = ("" . glow_dynamic_url_base('home') . "seller/user/").$username;
			$arrayUploadPath = wp_upload_dir();
			$fileUploadSubDir = $arrayUploadPath['basedir']."/qrcodes/image_".$user_id.".png";
			QRcode::png($text, $fileUploadSubDir);
			$fileUpload_full_link = $arrayUploadPath['baseurl']."/qrcodes/image_".$user_id.".png";
			if(!empty($fileUpload_full_link)) {
				update_user_meta($user_id,'seller_barcode_link',$fileUpload_full_link);
			}
			/* End */
		
	  ?>
	   <p style="color:#333;font-size:16px;margin-top:10px;">⚠️ Don’t hit delete! 💌 This email has everything you need for your fundraiser & seller dashboard — your key to glowing success! ✨</p>
      </td>
    </tr>

    <tr>
      <td style="padding:30px 40px;padding-top:10px;color:#333;line-height:1.6;font-size:15px;">
        <p>Thank you for registering as a <b>Glow Scented Candles Seller</b> and supporting <b><?php echo $org; ?></b>! 🌙<br>
        You’re helping your team shine bright while earning profits through beautiful candles, room sprays, wax melts, and essential oils.</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h3 style="color:#333;margin-top:30px;">🔍 Your Seller Details</h3>
        <?php if(!empty($fileUpload_full_link)) { ?>
          <p><b>📸 Profile QR Code:</b><br><img src="<?php echo $fileUpload_full_link; ?>" alt="Profile QR Code" style="width:110px;border-radius:6px;margin:10px 0;"></p>
        <?php } ?>
        <p><b>🔗 Your Seller Link:</b> <a href="<?php echo ("" . glow_dynamic_url_base('home') . "seller/user/").$username; ?>" target="_blank" style="color:#4184f3;text-decoration:none;"><?php echo ("" . glow_dynamic_url_base('home') . "seller/user/").$username; ?></a></p>
        <p><b>📧 Email:</b> <?php echo $user_email; ?></p>
        <p><b>🔑 Password:</b> <?php echo $character[3]->value; ?></p>
        <p><b>🪪 Fundraiser ID:</b> <?php echo $fID; ?></p>
        <p><b>🎉 Group Name:</b> <?php echo $org; ?></p>
        <p><b>💵 Seller Goal:</b> $<?php echo $character[5]->value; ?></p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h3 style="margin-top:35px;">🚀 Let’s Get Started!</h3>
        <p>🖥️ Log in to your Seller Dashboard.</p>

        <p><b>👉 My Account Login:</b> <a href="<?php echo ("" . glow_dynamic_url_base('home') . "my-account/?email=").$user_email."&pass=".$character[3]->value; ?>" target="_blank" style="color:#4184f3;">Access Dashboard</a></p>

        <p>Here, you’ll see real-time updates of your sales and success!</p>

        <h4 style="margin-top:25px;">✅ Start Sharing Your Link Right Away</h4>
        <ol style="padding-left:20px;">
          <li>Copy your Seller Link: <a href="<?php echo ("" . glow_dynamic_url_base('home') . "seller/user/").$username; ?>" target="_blank" style="color:#4184f3;"><?php echo ("" . glow_dynamic_url_base('home') . "seller/user/").$username; ?></a></li>
          <li>Share your link via email, text, and social media — include why you’re fundraising, your goal, and a photo/logo.</li>
        </ol>
        <p style="font-style:italic;">💡 The more you share, the more you sell!</p>
        <p style="font-style:italic;">📲 Spread the Glow! Tap to share on Facebook, Instagram, or copy the link.</p>
        <h4 style="margin-top:25px;">✨ Glow Up Your Fundraiser — One Post at a Time!</h4>
        <h4 style="margin-top:25px;">Ready, set, post! 🚀 These images make promoting your Glow easy & fun 👇</h4>
        <a href="https://www.dropbox.com/scl/fo/2qaa5ldadzp5nnu0l4acn/AF2UoshWPeClkWYJkxHHpAM?rlkey=ex6gonh1c6hvx1ojit5dxu94v&st=ubblc6lz&dl=0" target="_blank" style="color: blue; font-weight:700;font-size:15px;text-align: left;">Download Glow Product Images Here</a>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">🌟 Keep Your Momentum Going</h4>
        <ul style="padding-left:20px;">
          <li>📣 Send your link every few days to family & friends:<br/><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>seller/user/tulip1936">🔗 <?php echo esc_url( glow_dynamic_url_base('home') ); ?>seller/user/tulip1936</a><br/><em>People get busy and forget — friendly reminders keep your fundraiser shining!</em></li>
          <li>📱 Post, text, and email often for maximum visibility and support.</li>
          <li>💛 Remember: Each share is a chance to make a sale and help your cause glow brighter.</li>
        </ul>

        <h4 style="margin-top:30px;">📣 Final Push</h4>
        <ul style="padding-left:20px;">
          <li>Send a “Last Chance to Order!” reminder a few days before your sale ends.</li>
        </ul>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">🛍️ Spread the Glow</h4>
        <p style="margin-top:25px;">✨ Candles, Room Sprays, Wax Melts, and Essential Oils make perfect gifts for every occasion!</p>
        <p style="margin-top:25px;">🎁 Invite supporters to shop online for more scents and exclusive items.</p>
        <p style="margin-top:25px;">🚚 Free Shipping on orders $99+, or just $11.95 for smaller orders.</p>
        <p style="margin-top:25px;">📦 Orders ship in 3–5 business days or can ship free to your group at the end of your fundraiser!</p>

        <hr style="border:0; border-top:1px double #edeae9; width:100%; margin:20px auto;">

        <h4 style="margin-top:30px;">💫 You’ve Got This!</h4>
        <p style="margin-top:25px;">We’re so excited to see your success and help your team shine bright!</p>
        <p style="margin-top:25px;">Keep sharing, keep glowing, and remember — every candle sold helps your cause sparkle. ✨</p>
        <p style="color:#555;margin-top:20px;border-top:1px solid #eee;padding-top:15px;">
          💌 <b>Happy Selling,</b><br>
          💛 The Glow Team<br>
          <!-- <a href="mailto:contact@glowscentedcandles.com" style="color:#4184f3;">contact@glowscentedcandles.com</a><br>
          <a href="<?php echo esc_url( glow_dynamic_url_base('root') ); ?>" target="_blank" style="color:#4184f3;">www.glowscentedcandles.com</a> -->
        </p>
      </td>
    </tr>
  </table>
</div>
<?php } else { }  ?>
</body>
</html>
	<?php 
	$body= ob_get_clean();
	$m_body=stripslashes($body);
   	$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
$attachments = [];
if (!empty($docx_path)) {
    $attachments[] = $docx_path;
}
	wp_mail('akshaywalia770@gmail.com', $subject, $m_body, $headers,$attachments);
$admin_email = array(
    "candace@glowscentedcandles.com",
    "candacethompson12@gmail.com"
);
	wp_mail($rmail, $subject, $m_body, $headers,$attachments);
	wp_mail($admin_email, $subject, $m_body, $headers,$attachments); 

	/* Referral Recipients */
	if(!empty($referral_email))
	{
		if (wp_mail($referral_email, $subject, $m_body, $headers,$attachments)) 
		{
			error_log("email has been successfully sent to user whose email is " . $referral_email);
		}
		else
		{
		  error_log("email failed to sent to user whose email is " . $referral_email);
		}
	}
	/* Seller Registered */
	if(!empty($recipient_main))
	{
		if (wp_mail($recipient_main, $subject, $m_body, $headers,$attachments)) {
		  error_log("email has been successfully sent to user whose email is " . $recipient_main);
		}
		else
		{
		  error_log("email failed to sent to user whose email is " . $recipient_main);
		}
	}
	/* Registered Recipients */
	if (wp_mail($user_email, $subject, $m_body, $headers,$attachments)) {
      error_log("email has been successfully sent to user whose email is " . $user_email);
	  if($check=="fund")
	  {
		  /*sleep(60);*/
		 send_email_after_few_mintues($user_id, $user_full_name);
	  }
    }
	else
	{
      error_log("email failed to sent to user whose email is " . $user_email);
    }
	
	/* Multiple Recipients */
	if(!empty($multiple_recipients))
	{
		if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) 
		{
		  error_log("email has been successfully sent to user whose email is " . $multiple_recipients);
		}
		else
		{
		  error_log("email failed to sent to user whose email is " . $multiple_recipients);
		}
	}
	
}
	
}
// THE ONLY DIFFERENCE IS THIS LINE
// GLOW: disabled on the new build — depends on old Royal $_POST['form_data'] and is
// superseded by glow_email_user_fundraiser_welcome (new UR-Pro registration flow).
// add_action('user_register', 'send_welcome_email_to_new_user',40,1);

/* Custom Fields Fundraiser registered */ 
	function custom_fundraiser_fields($user_id) {
    $user = get_userdata($user_id);
    $user_email = $user->user_email;
    // for simplicity, lets assume that user has typed their first and last name when they sign up
	$character = json_decode(stripslashes(isset($_POST['form_data']) ? (string)$_POST['form_data'] : ''));
    $user_full_name =  $character[0]->value.' '.$character[1]->value;
	$org=$character[2]->value;
	$before=$character[15]->value; 
	$hear= $character[3]->value;
	$referral= $character[4]->value;
	$type=$character[5]->value;
	$Participants= $character[8]->value;
	$start_date=$character[9]->value;
    $end_date=$character[10]->value; 
	$goal_amount=$character[11]->value;
	$go_toward=$character[12]->value;  
	$selling_goal=$character[13]->value; 
	$payable=$character[14]->value;
	$org_label=$character[2]->label;
	 if($org_label=="Organisation Name"){
		$post_id = wp_insert_post(array('post_title'=>'fundraiser-'.$user_id, 'post_type'=>'fundraiser'));		
		update_user_meta($user_id, 'fundraiser_post_id', $post_id);
        update_post_meta($post_id, 'organizations_name', $org);
		update_post_meta($post_id, 'directors_first_n_last_name', $user_full_name);
		update_post_meta($post_id, 'glow_fundraiser_before', $before);
		update_post_meta($post_id, 'how_hear_about_glow', $hear);
		update_post_meta($post_id, 'favorite_type_of_candle_scents', $type);
		update_post_meta($post_id, 'email', $user_email);
		update_post_meta($post_id, 'number_of_participants', $Participants);
		update_post_meta($post_id, 'start_date', $start_date);
		update_post_meta($post_id, 'end_date', $end_date);
	    update_post_meta($post_id, 'fundraising_goal', $goal_amount);
		update_post_meta($post_id, 'funds_go_toward', $go_toward);
		update_post_meta($post_id, 'selling_goal_num_of_items', $selling_goal);
		update_post_meta($post_id, 'checks_made_payable_to', $payable);
		update_post_meta($post_id, 'fundraiser_id', $user_id);
		
	}
	}

  // THE ONLY DIFFERENCE IS THIS LINE
  // GLOW: disabled on the new build — depends on old Royal $_POST['form_data'] (no-op
  // on UR-Pro) and is superseded by glow_handle_fundraiser_form_submission.
  // add_action('user_register', 'custom_fundraiser_fields',10, 1);
// Add the custom columns to the fundraiset post type:
add_filter( 'manage_fundraiser_posts_columns', 'set_custom_edit_fundraiser_columns' );
function set_custom_edit_fundraiser_columns($columns) {
    /*unset( $columns['author'] );*/
	  $columns['organizations_name'] = __( 'Organizations Name', 'your_text_domain' );
    $columns['start_date'] = __( 'Start Date', 'your_text_domain' );
    $columns['end_date'] = __( 'End Date', 'your_text_domain' );

    return $columns;
}

// Add the data to the custom columns for the fundraiset post type:
add_action( 'manage_fundraiser_posts_custom_column' , 'custom_fundraiser_column', 10, 2 );
function custom_fundraiser_column( $column, $post_id ) {
    switch ( $column ) {
         case 'organizations_name' :
            echo get_post_meta( $post_id , 'organizations_name' , true ); 
            break;
		
        case 'start_date' :
            echo get_post_meta( $post_id , 'start_date' , true ); 
            break;

        case 'end_date' :
            echo get_post_meta( $post_id , 'end_date' , true ); 
            break;

    }
}
if ( ! function_exists( 'setup_theme_admin_menus' ) ) {
function setup_theme_admin_menus()
{
	add_menu_page('Email Settings', 'Email Settings', 'manage_options', 'edit_email', 'theme_edit_email_options');
	add_menu_page('Fundraiser Operations', 'Fundraiser Operations', 'manage_options', 'fundraiser_operations', 'theme_fundraiser_operations_options');
	add_menu_page('Fundraiser Outstanding', 'Fundraiser Outstanding', 'manage_options', 'fundraiser_outstanding', 'theme_fundraiser_outstanding_options');
	add_menu_page('Send Check Orders', 'Send Check Orders', 'manage_options', 'send_check_orders', 'theme_fundraiser_send_check_orders');
	add_menu_page('Email Tracking', 'Email Tracking', 'manage_options', 'email_tracking', 'theme_fundraiser_email_tracking');
	add_menu_page('Booking List', 'Booking List', 'manage_options', 'booking_list', 'theme_fundraiser_booking_track');
	add_menu_page('Representative', 'Representative', 'manage_options', 'representative', 'custom_representative_list_data','dashicons-admin-users');
    add_submenu_page("representative","Add New Reps","Add New Reps",'manage_options',"new_user_custom-submenu-page", "custom_add_new_reps_submenu_page_callback");

}
}
add_action("admin_menu", "setup_theme_admin_menus");


if ( ! function_exists( 'theme_edit_email_options' ) ) {
function theme_edit_email_options()
{
	if ( isset( $_POST['save_email_settings'] ) ) {
		if ( isset( $_POST['customerCleanerMail'] ) ) {
			update_option( 'name_option_you_want', wp_unslash( $_POST['customerCleanerMail'] ) );
		}
		if ( isset( $_POST['customerCleanerMail2'] ) ) {
			update_option( 'name_option_you_want_seller', wp_unslash( $_POST['customerCleanerMail2'] ) );
		}
		if ( isset( $_POST['customerCleanerMail3'] ) ) {
			update_option( 'name_option_you_want_sales', wp_unslash( $_POST['customerCleanerMail3'] ) );
		}
		echo '<div class="notice notice-success is-dismissible"><p>Email templates saved successfully.</p></div>';
	}

	$fundraiser_email = get_option( 'name_option_you_want', '<p>Mail format</p>' );
	$seller_email = get_option( 'name_option_you_want_seller', '<p>Mail format</p>' );
	$sales_email = get_option( 'name_option_you_want_sales', '<p>Mail format</p>' );
?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Fundraiser Email Notification</h1>
		<hr class="wp-header-end">
		
		<form action="" method="post" style="max-width: 1100px; margin-top: 20px;">
			<input type="hidden" name="save_email_settings" value="1">
			
			<div class="postbox" style="margin-bottom: 25px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
				<div class="postbox-header" style="border-bottom: 1px solid #ccd0d4; padding: 10px 15px; background: #f6f7f7;">
					<h2 class="hndle" style="font-size: 16px; margin: 0; font-weight: 600;">Fundraiser Email Notification</h2>
				</div>
				<div class="inside" style="padding: 15px;">
					<?php wp_editor( $fundraiser_email, 'customerCleanerMail', array( 'textarea_name' => 'customerCleanerMail', 'media_buttons' => true, 'editor_height' => 250, 'teeny' => false ) ); ?>
				</div>
			</div>

			<div class="postbox" style="margin-bottom: 25px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
				<div class="postbox-header" style="border-bottom: 1px solid #ccd0d4; padding: 10px 15px; background: #f6f7f7;">
					<h2 class="hndle" style="font-size: 16px; margin: 0; font-weight: 600;">Seller Email Notification</h2>
				</div>
				<div class="inside" style="padding: 15px;">
					<?php wp_editor( $seller_email, 'customerCleanerMail2', array( 'textarea_name' => 'customerCleanerMail2', 'media_buttons' => true, 'editor_height' => 250, 'teeny' => false ) ); ?>
				</div>
			</div>

			<div class="postbox" style="margin-bottom: 25px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
				<div class="postbox-header" style="border-bottom: 1px solid #ccd0d4; padding: 10px 15px; background: #f6f7f7;">
					<h2 class="hndle" style="font-size: 16px; margin: 0; font-weight: 600;">Sales Email Notification</h2>
				</div>
				<div class="inside" style="padding: 15px;">
					<?php wp_editor( $sales_email, 'customerCleanerMail3', array( 'textarea_name' => 'customerCleanerMail3', 'media_buttons' => true, 'editor_height' => 250, 'teeny' => false ) ); ?>
				</div>
			</div>

			<?php submit_button( 'Save Email Settings', 'primary large' ); ?>
		</form>
	</div>
<?php
}
}
// ADD SHIPPING COST
/*
function woo_add_cart_fee() {

  WC()->cart->add_fee( __('Shipping', 'woocommerce'), 100 );

}

add_action( 'woocommerce_cart_calculate_fees', 'woo_add_cart_fee' );*/
function disable_shipping_calc_on_cart( $show_shipping ) {
	$user = wp_get_current_user();
	    $minimum = 50;

  

	 if( is_checkout() )
	 {
		   if ( WC()->cart->total > $minimum ) 
		   {
              return false;
		   }
		   else
		   {
			   return true;
		   }
    }


    return $show_shipping;
}
//add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );
// Change new order email recipient for registered customers
/*function wc_change_admin_new_order_email_recipient( $recipient, $order ) {
    global $woocommerce;
     $recipient = "3dgeeksin@gmail.com";
    
    return $recipient;
}
add_filter('woocommerce_email_recipient_new_order', 'wc_change_admin_new_order_email_recipient', 1, 2);*/
function custom_new_order_email($order_id)
{
/*	$user = wp_get_current_user();*/
	$slrID     = get_post_meta($order_id, 'fundraiser_field', true );
	$action_status=get_user_meta( $slrID , 'user_action_status' , true );
	if ( $action_status==1 || empty($action_status) ) {
	
	$referral_email     = get_user_meta($slrID, 'user_registration_referral_email', true );
// 	$group_name=get_user_meta( $slrID, 'user_registration_input_company', true );
$group_name=get_user_meta( $slrID, 'user_registration_name_of_school_business', true );
	$sellerid=get_post_meta( $order_id, 'wc_seller_field', true );
	$orderType=get_post_meta( $order_id, 'wc_order_type', true );
	if($orderType=="Catlog")
	{
		 if($sellerid=="custom")
		 {
			 $full_name=get_post_meta( $order_id, 'wc_seller_field_custom', true );
		 }
		 else if(is_numeric($sellerid))
		 {
		
		 $user_info = get_userdata($sellerid);
		 $first_name = $user_info->first_name;
		 $last_name = $user_info->last_name;
		 $full_name= $first_name." ". $last_name;
		}
		else
		{
			$full_name="";
		}
	}
	else
	{
		 if($sellerid=="custom")
		 {
			 $full_name=get_post_meta( $order_id, 'wc_seller_field_custom', true );
		 }
		 else if(is_numeric($sellerid))
		 {
		
		 $user_info = get_userdata($sellerid);
		 $first_name = $user_info->first_name;
		 $last_name = $user_info->last_name;
		 $full_name= $first_name." ". $last_name;
		}
		else
		{
			$full_name=$sellerid;
		}
	}
	$checkbox=get_post_meta( $order_id, 'wc_checkbox_field', true );
    $slr_info  = get_userdata($slrID);
	$user_email = $slr_info->user_email;
	if(is_numeric($sellerid))
	{
		$slr_info1  = get_userdata($sellerid);
	    $seller_email = $slr_info1->user_email;
	}
    /*$to= $referral_email;*/
	if(!empty($seller_email) && !empty($referral_email))
	{
		$multiple_recipients = array(
					$referral_email,
					$seller_email,
					$user_email
				);
			
	}
	else if(empty($seller_email) && !empty($referral_email))
	{
		$multiple_recipients = array(
					$referral_email,
					$user_email
				);
			
	}
	else if(!empty($seller_email) && empty($referral_email))
	{
		$multiple_recipients = array(
					$seller_email,
					$user_email
				);
			
	}
	else
	{
		$multiple_recipients = "";
			
	}
	
	//  $subject = "Glow Scented Candles - Fundraiser Order";
	
	if($orderType=="Online")
	{
		 $subject = "Glow Scented Candles - Online Order (# ".$order_id.")";
		 $heading = "Glow Scented Candles - Online Order #".$order_id;
	}
	else if($orderType=="ship-to-school")
	{
		 $subject = "Glow Scented Candles - Ship to School/Group Order (# ".$order_id.")";
		 $heading = "Glow Scented Candles - Ship to School/Group Order #".$order_id;
	}
	else if($orderType=="Catlog")
	{
		 $subject = "Glow Scented Candles - Brochure Order (# ".$order_id.")";
		 $heading = "Glow Scented Candles - Brochure Order #".$order_id;
	}
	else{
		 $subject = "Glow Scented Candles - Fundraiser Order (# ".$order_id.")";
		 $heading = "Glow Scented Candles - Fundraiser Order #".$order_id;
	}
	
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
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="600px" bgcolor="#fff" style="background-color:#f7f7f7;margin:0 auto;padding:70px 0 70px 0;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
			   <img style="width: 180px;" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/10/unnamed-1.png">
				
		  	</td>
		</tr>
		 <tr >
           <td align="left" style="padding:48px 48px 0; background:#fff;">
		   <h1 style="color: #333;font-size: 25px;font-weight: 600;line-height: 1.3;padding-bottom: 5px;"><?php echo $heading; ?></h1>
	   <p style="margin:0 0 16px">Your order has been received and is now being processed. Your order
		details are shown below for your reference:</p>
		<h2>
	<?php
	global $woocommerce;
    if ( !$order_id )
    return;
   // $order = new WC_Order( $order_id );
    $order = wc_get_order( $order_id );
	//$itemss = wc_new_orders( $order_id );
	/*print_r($itemss);*/
	$text_align='center';
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( 'Order #%s', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	?>
</h2>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
		
		<?php 
		
			$text_align = is_rtl() ? 'right' : 'left';
			
			global $woocommerce;
	$id=$order_id;
	$order = new WC_Order( $id );
	$items = $order->get_items();

/*Seller name */
$seller_name= array();
	foreach ( $items as $item_id => $item ) :
	$s_name=$item['_wdm_seller_name'];
	if(!in_array($s_name,$seller_name))
	{
		$seller_name[]=$s_name;
	}
	endforeach; 
	if(empty($seller_name)) 
	{
		$order_type="Online";
	}
	else
	{
		$order_type="Catlog";
	}	
	if($order_type=="Catlog")
	{
		for($i=0;$i<count($seller_name);$i++)
		{	
			foreach ( $items as $item_id => $item ) :
				$product       = $item->get_product();
				$sku           = '';
				$purchase_note = '';
				$image         = '';

				if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					continue;
				}

				if ( is_object( $product ) ) {
					$sku           = $product->get_sku();
					$purchase_note = $product->get_purchase_note();
					$image         = $product->get_image( $image_size );
					$price=$product->get_price();
				}
			$s_name=$item['_wdm_seller_name'];
	?>
	<?php if($seller_name[$i]!=$title2){ ?>
		<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
									<td  class="td"><?php echo '<b>Seller Name: '.$seller_name[$i].'</b><br />';?></td>
									<td  class="td"></td>
									<td  class="td"></td>
									
								</tr>
	<?php  } $title2 = $seller_name[$i];
	if($s_name==$seller_name[$i]){
	?>
	<tr id="payment_complete_email" class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
		<?php

		// Show title/image etc.
		if ( $show_image ) {
			echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
		}

		// Product name.
		echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

		// SKU.
		if ( $show_sku && $sku ) {
			echo wp_kses_post( ' (#' . $sku . ')' );
		}

		// allow other plugins to add additional product information here.
		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

		wc_display_item_meta( $item );

		// allow other plugins to add additional product information here.
		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

		?>
		</td>
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?>
		</td>
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php /* echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) );
			echo $item->get_price(); */	?>
			 <?php echo "$".$price; ?>
			<?php 
		/*	if(is_float($price))
			{
				echo "$".$price; 
			}
			else
			{
				echo "$".$price.".00";
			}*/
			?>
		</td>
	</tr>
	<?php }

	if ( $show_purchase_note && $purchase_note ) {
		?>
		<tr>
			<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
				<?php
				echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
				?>
			</td>
		</tr>
		<?php
	}
	?>

<?php endforeach; } }
else {

foreach ( $items as $item_id => $item ) :
	$product       = $item->get_product();
	$sku           = '';
	$purchase_note = '';
	$image         = '';

	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		continue;
	}

	if ( is_object( $product ) ) {
		$sku           = $product->get_sku();
		$purchase_note = $product->get_purchase_note();
		$image         = $product->get_image( $image_size );
		$price=$product->get_price();
	}

	?>
	<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
		<?php

		// Show title/image etc.
		if ( $show_image ) {
			echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
		}

		// Product name.
		echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

		// SKU.
		if ( $show_sku && $sku ) {
			echo wp_kses_post( ' (#' . $sku . ')' );
		}

		// allow other plugins to add additional product information here.
		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

		wc_display_item_meta( $item );

		// allow other plugins to add additional product information here.
		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

		?>
		</td>
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?>
		</td>
		<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php /* echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) );
			echo $item->get_price(); */	?>
			 <?php echo "$".$price; ?>
			<?php 
		/*	if(is_float($price))
			{
				echo "$".$price; 
			}
			else
			{
				echo "$".$price.".00";
			}*/
			?>
		</td>
	</tr>
	<?php

	if ( $show_purchase_note && $purchase_note ) {
		?>
		<tr>
			<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
				<?php
				echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
				?>
			</td>
		</tr>
		<?php
	}
	?>
<?php endforeach;  } ?>
		</tbody>
		<tfoot>
			<?php
			$totals = $order->get_order_item_totals();

			if ( $totals ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( wptexturize( $order->get_customer_note() ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>
	<h3>Additional information</h3>
	<table>
		<tr>
			<td>Are you purchasing to support a fundraiser?: </td>
			<td><?php echo wptexturize( get_post_meta( $order->id, 'wc_checkbox_field', true ) ); ?> </td>
		</tr>
		<?php if( $checkbox=="Yes") { ?>
		<tr>
			<td>Fundraiser ID: </td>
			<td><?php echo wptexturize( $slrID ); ?></td>
		</tr>
		<?php if(!empty($full_name)){?>
		<tr>
			<td>Seller Name: </td>
			<td><?php echo $full_name; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Group Name: </td>
			<td><?php echo wptexturize( $group_name ); ?></td>
		</tr>
		<?php } ?>
	</table>
	<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo $text_align; ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2><?php _e( 'Billing address', 'woocommerce' ); ?></h2>

			<address class="address">
				<?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<br/><?php echo esc_html( $order->get_billing_phone() ); ?>
				<?php endif; ?>
				<?php if ( $order->get_billing_email() ) : ?>
					<p><?php echo esc_html( $order->get_billing_email() ); ?></p>
				<?php endif; ?>
			</address>
		</td>
		<?php  
		$orderType_a=get_post_meta( $order_id, 'wc_order_type', true );
		if($orderType_a=="ship-to-school") {
			
			$IDT= get_post_meta( $order_id, 'fundraiser_field', true );	
			$fname =  get_user_meta( $IDT, 'first_name', true );
			$lname = get_user_meta( $IDT, 'last_name', true );
			$company  = get_user_meta( $IDT, 'user_registration_name_of_school_business', true );
			$user_info = get_userdata($IDT);
			$email = $user_info->user_email;
			$address_1 = get_user_meta( $IDT, 'user_registration_street_address', true );
			$address_2  = "";
			$city = get_user_meta( $IDT, 'user_registration_City2', true );
			$phone  = get_user_meta( $IDT, 'user_registration_input_box_phone', true );
			$postcode  = get_user_meta( $IDT, 'user_registration_input_box_zip', true );
			$country  =  get_user_meta( $IDT, 'billing_country', true );
			$state  =  get_user_meta( $IDT, 'user_registration_select_state', true );

			$countries = new WC_Countries(); // Get an instance of the WC_Countries Object
			$countries_array = $countries->get_countries();
			$country_name = $countries_array[$country];
		
			if(empty($fname))
			{
				$fname="";
			}
			if(empty($lname))
			{
				$lname="";
			}
			if(empty($company))
			{
				$company="";
			}
			if(empty($email))
			{
				$email="";
			}
			if(empty($address_1))
			{
				$address_1="";
			}
			if(empty($city))
			{
				$city="";
			}
			if(empty($postcode))
			{
				$postcode="";
			}
			if(empty($country_name))
			{
				$country_name="";
			}
			if(empty($state))
			{
				$state="";
			}
		
		?>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>
			<address class="address">
					<?php if(!empty($company)) { echo $company."<br />"; } ?>
					<?php if(!empty($fname) || !empty($lname)) { echo $fname." ".$lname."<br />"; } ?>
					<?php if(!empty($address_1)) { echo $address_1."<br />"; } ?>
					<?php if(!empty($city) || !empty($state) || !empty($postcode)){ echo $city." ".$state." ".$postcode."<br />"; } ?>
			</address>
		</td>
		<?php
	   }
		else {
		?>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>
			<td style="text-align:<?php echo $text_align; ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
				<h2><?php _e( 'Shipping address', 'woocommerce' ); ?></h2>

				<address class="address"><?php echo $shipping; ?></address>
			</td>
		<?php endif; ?>
		<?php } ?>
	</tr>
</table>
	</div>
              
	  </td>
		</tr>
        </table>
	
    </div>
	</body>
</html>
	<?php 
$body= ob_get_clean();
$m_body=stripslashes($body);
// $multiple_harcode_emails= array('candace@glowscentedcandles.com');
   	// $headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
    // if (wp_mail('candace@glowscentedcandles.com', $subject, $m_body, $headers)) {
    //   error_log("email has been successfully sent to user whose email is " . $user_email);
    // }else{
    //   error_log("email failed to sent to user whose email is " . $user_email);
    // }
	$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
    if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) {
      error_log("email has been successfully sent to user whose email is " . $user_email);
    }else{
      error_log("email failed to sent to user whose email is " . $user_email);
    }
}
  }
 add_action( 'woocommerce_payment_complete', 'custom_new_order_email', 10, 1 );
 /*add_action( 'woocommerce_checkout_order_processed', 'custom_new_order_email',1,1); */
function wc_new_orders($order_id)
{
	global $woocommerce;
	$id=$order_id;
	$order = new WC_Order( $id );
	$items2 = $order->get_items();
	/* print_r($items); */
	return $items2;
}
/*
* Creating a function to send another email to Fundraiser Report 
*/
	add_action('wp_ajax_nopriv_custom_fund_report_another_mail', 'custom_fund_report_another_mail'); // for not logged in users
	add_action('wp_ajax_custom_fund_report_another_mail', 'custom_fund_report_another_mail');
	function custom_fund_report_another_mail( )
	{
		$ID = $_POST['fund_id'];
		$user_info = get_userdata($ID);
		$user_email = $_POST['a_email'];
		
		$to = $user_email;
		$subject = "Fundraiser Report!!";
		$body ='';
	ob_start();
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Fundraiser Report <?php echo "#".$ID; ?> </h1>
		  	</td>
		</tr>
			<?php
			$u_id=$ID;
			   $f=get_user_meta( $u_id , 'first_name' , true );		
			   $l=get_user_meta( $u_id , 'last_name' , true );								   
			 //  $org=get_user_meta( $u_id , 'user_registration_input_company' , true );
			 $org=get_user_meta( $u_id , 'user_registration_name_of_school_business' , true );
			   $sd=get_user_meta( $u_id , 'user_registration_start_date' , true );
			   $ed=get_user_meta( $u_id , 'user_registration_end_date' , true );
			   $referral=get_user_meta( $u_id , 'user_registration_referral_name' , true );
			   $Participants= get_user_meta( $u_id , 'user_registration_participants_no' , true );
			   $goal_amount=get_user_meta( $u_id , 'user_registration_fr_goal' , true );
			   $go_toward=get_user_meta( $u_id , 'user_registration_fund_destination' , true );  
			   $selling_goal=get_user_meta( $u_id , 'user_registration_selling_goal' , true );
			   $payable=get_user_meta( $u_id , 'user_registration_check_payable' , true );
				/* Rebate Total */
		$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                         /*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
									   $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								  }
								} 
								 else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									  $Discount = number_format($Discount, 2, '.', '');
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								 }
								} 
								else{}
								} }
                            /*     } */
						}
						
						/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						 // End Ship to school
                        ?>
						<tr>
             <td align="left" style="padding: 30px 30px;">
			 <div class="text-left" style="float:left;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Glow Scented Candles:</strong><br>
							3702 Cavalier Drive Suite 100<br>
							Garland TX 75042<br>
							<?php echo esc_url( glow_dynamic_url_base('home') ); ?><br>
							contact@glowscentedcandles.com<br>
							(972) 941-6874
				</address>
			</div>
			<div class="text-right" style="float:right;text-align:right;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong><?php echo $org; ?></strong><br>
							<?php echo $f; ?> <?php echo $l; ?><br>
				</address>
			</div>
			</td>
		</tr>
		 <tr>
             <td align="left" style="padding: 0px 30px;">
			 <div class="text-left" style="float:left;">
			 <?php 
			//$total_sale_fundraiser= number_format($total_sale_so_far, 2, '.', ''); 
			//$total_rebate_fundraiser= number_format($rebate_so_far, 2, '.', '');
			
			//Ship to School
			
			$total_sale_fundraiser = number_format($total_sale_so_far, 2, '.', ''); 
			$total_rebate_fundraiser = number_format($rebate_so_far, 2, '.', '');
			
			$total_sale_fundraiser2 = number_format($total_sale_so_far2, 2, '.', ''); 
			$total_rebate_fundraiser2 = number_format($rebate_so_far2, 2, '.', '');
			
			$total_sale_fundraiser3 = $total_sale_fundraiser + $total_sale_fundraiser2;
			
			$total_sale_fundraiser3 = number_format($total_sale_fundraiser3, 2, '.', '');
			
			$total_rebate_fundraiser3 = $rebate_so_far + $rebate_so_far2;
			
			$total_rebate_fundraiser3 = number_format($total_rebate_fundraiser3, 2, '.', '');
			
			//End Ship to school
			
			
			 ?>
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Period:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br>
							<!--<strong style="color: green;">Total Sale:</strong> <span style="color: green;"><?php echo "$".$total_sale_fundraiser;?></span><br>
							<strong style="color: green;">Total Rebate:</strong><span style="color: green;"><?php echo "$".$total_rebate_fundraiser; ?></span><br>-->
							<strong style="color: green;">Total Sale:</strong> <span style="color: green;"><?php echo "$".$total_sale_fundraiser3;?></span><br>
							<strong style="color: green;">Total Rebate:</strong><span style="color: green;"><?php echo "$".$total_rebate_fundraiser3; ?></span><br>
							<strong style="color: #333;font-size: 14px;">Note: A+B+C+D+F = Total Rebate and Total Sale.</strong><br>
				</address>
			</div>
			<div class="text-right" style="float:right;text-align:right;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Org Profit Check:</strong> <?php echo $org; ?><br>
							<strong>Fundraising Goal:</strong> <?php echo $goal_amount; ?><br>
							<strong>Funds Go Toward: </strong> <?php echo $go_toward; ?><br>
							<strong>Selling Goal (# of Items):</strong> <?php echo $selling_goal; ?><br>	
							<strong>Checks Made Payable To:</strong> <?php echo $payable; ?><br>
						</address>
			</div>
			</td>
		</tr>
		<!--<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>-->
		
		
		 <?php
		 
		 /* Start Print All Seller Order List */
                               $arr=array();
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
								
								 foreach ( $all_meta_for_users as $all_meta_for_user ) { 
								 $arr[]=$all_meta_for_user->ID;
								 }
								 
					$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) 
								  { 
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								  } } } }
						}
						 }
					?>
					
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
									 
								 
				<?php
					}
         foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border:0;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							    $item_qty=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							
                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
									/* total Item  Sold */
										foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data(); 
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else if($order_type=="") 
											{
												$item_qty  +=  $item_data['quantity']; 
											}
											else{}
																								
                                        endforeach; 
										
									/* total Item  Sold */
								  if ($frID3 != $order_customer_id) { 
                                 $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										  $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }
									  else
									  {
										$total_sale_so_far+=$order->get_subtotal();
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							} } }
                                  ?>
								  <?php  } 
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');  ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 
								
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
						<?php  }  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php }
		   
		    /* End Print All Seller Order List */
                              ?>
			<?php  /* Start Print Manually Add Seller Order List */ ?>
			<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border:0;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
                            if ($ID3 == "custom") { 

                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										  $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }
									  else
									  {
										$total_sale_so_far+=$order->get_subtotal();
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                  ?>
						<?php  } } } }
						}
						
						
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								    if ($in_s ==1){ 
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									}
									}
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count($seller_name);$i++) 
						{
						    $seller_rebate=0;
							$seller_total_sale=0;
							$item_qty_manual=0;
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){ 
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														    foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type=="Online") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else if($order_type=="") 
															{
																$item_qty_manual +=  $item_data['quantity']; 
															}
															else{}
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									} }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						$item_qty_arr[]=$item_qty_manual;
						}
						
						
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Seller</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');  ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							<?php
								$exist_seller=count($seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
								 <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count($seller_name);$i++)
							{ ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $seller_t=number_format($seller_total_Sale_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $seller_r=number_format($seller_rebate_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
						        <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_arr[$i]; ?></span></strong></td>
								</tr>
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) {
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php }  } } } }
                        }
							}
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		<?php  /* End Print Manually Add Seller Order List */ ?>
		<?php  /* Start Print Seller not mentioned Order List */ ?>
			<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border:0;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_not_found=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) {
									 $wc_is_mergeOrder = get_post_meta($order->get_id(), 'wc_seller_total_mergeOrder', true );
									 if(empty($wc_is_mergeOrder)) 
									 {	
									  
                            if (empty($ID3)) { 

                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										  $total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }
									  else
									  {
										$total_sale_so_far+=$order->get_subtotal();
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');
									  }

                                      foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											if($order_type=="Online") 
											{
												$item_qty_not_found +=  $item_data['quantity']; 
											}
											else if($order_type=="") 
											{
												$item_qty_not_found +=  $item_data['quantity']; 
											}
											else{}
                                    endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                     $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                  ?>
								  <?php  } } } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: Seller not mentioned</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');  ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							   <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_not_found; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							if ( $order->has_status('completed') || $order->has_status('processing')) {
								/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
                            if (empty($ID3)) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								  if ($frID3 != $order_customer_id) { 
								   $wc_is_mergeOrder = get_post_meta($order->get_id(), 'wc_seller_total_mergeOrder', true );
									 if(empty($wc_is_mergeOrder)) 
									 {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = "N/A";
                                        /*$slr_info  = get_userdata($slrID);*/
                                       ?>
                                       <?php echo $slrID; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_subtotal();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                     $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								  <?php  } }  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		<?php  /* End Print Seller not mentioned Order List */ ?>
		
			<!-- Fundraiser Online Sales-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="Online") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else if($order_type_2=="") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
						} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							   $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                              
									if ( $order->has_status('completed') || $order->has_status('processing')) {
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php  }  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
		 <!-- Catalog Sale -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  
							   $total_cat_quantity=0;
							  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  $new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true); 
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										
                                       $order_item_quantity = 0; 
									foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
										 $order_item_quantity += $item_data['quantity']; 	
										 $total_cat_quantity += $item_data['quantity'];  //ship to school													
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
										

								 }
							
							} }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: Fundraiser Catalogue Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th>
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true); 
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							    $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                             
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							     if ($order_type=='Catlog') { 
								   $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=number_format( $total_sale, 2); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS || !empty($new_catalog_order) && $new_catalog_order == "Yes")
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
		<!-- Online Ship to School -->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 100%;margin: 0px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
							   $item_qty_fund_online=0;
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();

									/* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                
                               
							  
						} } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far;
							$rebate_so_far = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far;
                            $rebate_so_far = number_format($Discount, 2, '.', '');
							
						}
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#ff5722;">F: Ship to School Orders</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							    <tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#ff5722;">Total Sold Items: <strong><span class="fr-total-rebate"><?php echo $item_qty_fund_online; ?></span></strong></td>
								</tr>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">Rebate</th> -->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ($frID3 != $order_customer_id)  
								{
									if ($scat==1) {
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
							     if ($order_type=='ship-to-school') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #ff5722;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;"> <?php 
                                     $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
										if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}
									  
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#ff5722;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							 <?php  } } } }
                        }
                        ?>
			 
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
		/*$headers = "From: Glow Scented Candles <info@glowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	if($mailSuc){
		echo "Your report has been successfully sent.";
	} else{
		echo "Error!!";
	}
	die();
		
	
		
	}
/*
* Creating a function to send another email to Fundraiser Report 
*/
	add_action('wp_ajax_nopriv_custom_fund_status', 'custom_fund_status'); // for not logged in users
	add_action('wp_ajax_custom_fund_status', 'custom_fund_status');
	function custom_fund_status( )
	{
		$ID = $_POST['fund_id'];
		$action = $_POST['st_ac'];
		$rebate=$_POST['rebate'];
		
		$glow_profit_deduct_user_metakey_yes=get_user_meta($ID, "glow_profit_deduct_user_metakey_yes", true);
		if($glow_profit_deduct_user_metakey_yes=="Yes")
		{
		    if($action==1)
			{
				 $rem=get_user_meta($ID, "user_rebate_remain_glowProfitDeduct", true); //Remaining fees
				 $rem = number_format($rem, 2, '.', '');
				 if(!empty($rem) && $rem>0)
				 {
					  $fees_paid=get_user_meta($ID, 'user_rebate', true );
					  if(!empty($fees_paid))
					  {
						$new_paid=$fees_paid+$rem;
						$new_paid_decimal = number_format($new_paid, 2, '.', '');
						update_user_meta( $ID, 'user_rebate' , $new_paid_decimal );
						update_user_meta( $ID, 'user_rebate_remainpaidAmt', $rem );
						update_user_meta( $ID, 'user_rebate_remainpaid', "Yes" );
					  } 
				 }
				 else{
					 
				 }
				
			}
			update_user_meta( $ID , 'user_status' , $action );
					
			wp_send_json_success(array(
					'action' => $action,
					'rebateSoFar'=>$rebateSoFar
					));
		}
		else {
		
		
		$rebate_so_far=0;
								$argss = array(
										'status'   => "published",
										'meta_key' => 'fundraiser_field',
										'meta_value' => $ID,
										'limit' => -1
									);
								$orders = wc_get_orders($argss);
								foreach ( $orders as $order ) {
									$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
									  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

								  /*  if ($ID3 == $IDD) { */
										$order_data = $order->get_data();
										$order_customer_id = $order_data['customer_id'];
										if ( $order->has_status('completed') || $order->has_status('processing')) {
											$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											  if($order_type=="Online") 
											  {
											/* Active/Inactive */	
											$order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
											$extract_date=$order_date;
											$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
											$inactive_s=get_user_meta($frID3, "user_action_status", true);
											$in_s=1;
											if ($inactive_date < $extract_date && $inactive_s==2)
											{
												$in_s=2;
											}
											else if($inactive_s==1 || empty($inactive_s))
											{
												$in_s=1;
											}
											else{
												$in_s=1;
											}
											/*$inactive_date="2020-08-29 01:00:01";*/
										if ($in_s ==1){
										  if ($frID3 != $order_customer_id) 
										  {
									   $total_sale_so_far+=$order->get_subtotal();
									 

											   foreach ($order->get_items() as $item_key => $item_values): 
														$item_data = $item_values->get_data();  
													 endforeach; 
											 
											$total_product = $order->get_total();   ///$order_data->get_total();


											$percentage  = 40;
											$percentFifty= 50;

										   $tdiscount=$order->get_total_discount();
												
											if(!empty($tdiscount))
											{
												 $newamt=$order->get_subtotal()-$tdiscount;
												 $totalAmount = $newamt;
											}
											else
											{
											 $totalAmount = $order->get_subtotal();
											}
								
									 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
										 $rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									   
									  
										   /*} */
								}
									else if ($frID3 == $order_customer_id) 
											{
											  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
											  if($order_type=="Online") 
											  {
												$tdiscount=$order->get_total_discount();
												
												if(!empty($tdiscount))
												{
												  $newamt=$order->get_subtotal()-$tdiscount;
												  $total_sale_so_far+=$newamt;
												}
												else
												{
												 $total_sale_so_far+=$order->get_subtotal();
												}
									 

											   foreach ($order->get_items() as $item_key => $item_values): 
														$item_data = $item_values->get_data();  
													 endforeach; 
											 
												$total_product = $order->get_total();   ///$order_data->get_total();


												$percentage  = 40;
												$percentFifty= 50;

											   $tdiscount=$order->get_total_discount();
													
												if(!empty($tdiscount))
												{
													 $newamt=$order->get_subtotal()-$tdiscount;
													 $totalAmount = $newamt;
												}
												else
												{
												 $totalAmount = $order->get_subtotal();
												}
									
										 
												 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
												 {
													$Discount    = ($percentFifty / 100) * $totalAmount;
													$Discount = number_format($Discount, 2, '.', '');
													$rebate_so_far+=$Discount;
												 }
												 else
												 {
													$Discount    = ($percentage / 100) * $totalAmount;
													$Discount = number_format($Discount, 2, '.', '');
													 $rebate_so_far+=$Discount;
												 }
											}
										} 
										else{}

								  } } } }
			
		/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentageNEW  = 40;
                        $percentFiftyNEW= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFiftyNEW / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentageNEW / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						 // End Ship to school
								  
								  
		/*Total NEW Rebate with Ship to school orders */
		
		$rebateSoFar = $rebate_so_far+$rebate_so_far2;
		$rebateSoFar = number_format($rebateSoFar, 2, '.', '');			
		
		if($action==1)
		{
		update_user_meta( $ID , 'user_rebate' , $rebateSoFar );
		}
		update_user_meta( $ID , 'user_status' , $action );
		/*echo "Your Status has been successfully updated.";*/
		wp_send_json_success(array(
		'action' => $action,
		'rebateSoFar'=>$rebateSoFar
		));
	}
	}
/*Checkout*/
add_action( 'woocommerce_checkout_process', 'wc_seller' );
function wc_seller() {
	if($_POST['wc_checkbox_field']=="Yes")
	{
		global $woocommerce;

		$coupon_code = 'Friends25'; // coupon code
		$coupon_code2 = 'glow50';
		//$applied_coupon=$woocommerce->cart->get_applied_coupons();
		/*$WC_Cart = new WC_Cart();
		$applied_coupon= $WC_Cart->get_applied_coupons();
		$coupon = new WC_Coupon( $applied_coupon );
		$id=$coupon->get_id();*/
		foreach ( WC()->cart->get_coupons() as $code => $coupon ) :
		$coupon = new WC_Coupon( $code );
		$id=$coupon->get_id();
		endforeach;

		$no_fundraiser= get_post_meta($id, 'coupon_non_fundraiser', true);
		if($no_fundraiser == "Yes")
		{
		/*if ( $woocommerce->cart->has_discount( $coupon_code ) || $woocommerce->cart->has_discount( $coupon_code2 ) )
		{*/
			 wc_add_notice( 
                sprintf( 'Coupon not valid for fundraising order. Please Select No option in field "Are you purchasing to support a fundraiser?' 
                ), 'error' 
            );
		}
		else
		{
			if(isset($_POST['fundraiser_field']) && $_POST['fundraiser_field'] == "")
			{
				 wc_add_notice( 
						sprintf( 'Please Select Fundraiser' 
						), 'error' 
					);
				
			}
			if(strlen(isset($_POST['fundraiser_field']) ? (string)$_POST['fundraiser_field'] : '')<6 || strlen(isset($_POST['fundraiser_field']) ? (string)$_POST['fundraiser_field'] : '')>6)
			{
				wc_add_notice( 
						sprintf( 'Please Enter/select 6 Digits Fundraiser Code.' 
						), 'error' 
					);
			}
			if(!empty($_POST['fundraiser_field']))
			{
				$query=$_POST['fundraiser_field'];
				if ( preg_match( '/([0-9]{6})/', $query) == 1) 
			    {
					$fundr_ID=$_POST['fundraiser_field'];
					$user_info = get_userdata($fundr_ID);
				
					if(!in_array( 'fundraiser', (array) $user_info->roles ))
					{
					
					wc_add_notice( 
							sprintf( 'Incorrect entered fundraiser code.' 
							), 'error' 
						); 
						
					}
					else if(in_array( 'fundraiser', (array) $user_info->roles ))
					{
						$fundr_status = get_user_meta( $fundr_ID, 'user_action_status' , true );
						$fundr_preference = get_user_meta( $fundr_ID, 'user_registration_fundraiser_preference' , true );
						$first_catalog_order = get_user_meta( $fundr_ID, 'wc_first_catlog_order', true);
						if($fundr_status==2 && !is_user_logged_in())
						{
						wc_add_notice( 
								sprintf( 'Fundraiser is inactive.' 
								), 'error' 
							); 
							
						}
						else if($fundr_status==2 && is_user_logged_in() && $_SESSION['sellername']=='Online' )
						{
							wc_add_notice( 
									sprintf( 'Fundraiser is inactive.' 
									), 'error' 
								); 
							
						}
						else
						{
							
						}
						/* Error on Ship to school order */
						if($fundr_status!=2 && $_POST['installment_fee']==1 && glow_is_virtual_preference($fundr_preference))
						{
							
							wc_add_notice( 
									sprintf( "Ship to school feature is not available for this fundraiser. It's virtual fundraiser group." 
									), 'error' 
								); 
						}
						else if($fundr_status==2 && $_POST['installment_fee']==1)
						{
							wc_add_notice( 
									sprintf( 'The fundraising campaign has ended. Orders for Ship to School cannot be placed.' 
									), 'error' 
								); 
							
						}
						else if($first_catalog_order=="Yes" && $_POST['installment_fee']==1)
						{
							wc_add_notice( 
									sprintf( 'Ship to school feature is closed for this fundraiser.' 
									), 'error' 
								); 
							
						}
						else
						{
							
						}
						
					}
					else
					{
						
					}
				}
			}
			if(!empty($_SESSION['sellername']) && is_user_logged_in() && $_SESSION['sellername']=='Online') 
			{
				$userNew = wp_get_current_user();
				$user_info_new = get_userdata($userNew->ID);
				if(in_array('fundraiser', (array) $user_info_new->roles ))
				{
					wc_add_notice( 
							sprintf( 'You cannot place an online order as a fundraiser.' 
							), 'error' 
						);
				}
			}				
			if(!empty($_SESSION['sellername']) && $_SESSION['sellername']!='Catlog') 
			{
				if ( isset($_POST['wc_seller_field']) && $_POST['wc_seller_field']=="No-seller" ) {

					
						wc_add_notice( 
							sprintf( 'Please Select Seller' 
							), 'error' 
						);

				   
				}
				if(isset($_POST['wc_seller_field']) && empty($_POST['wc_seller_field']))
				{
					 wc_add_notice( 
							sprintf( 'Please Select Seller' 
							), 'error' 
						);
					
				}
				if ( isset($_POST['wc_seller_field']) && $_POST['wc_seller_field']=="custom" && empty($_POST['wc_seller_field_custom']) ) {

				
					wc_add_notice( 
						sprintf( 'Please Enter Seller Name' 
						), 'error' 
					);

			   
				}
			}
			
			
		}
	}
	if(isset($_POST['billing_phone']) && !empty($_POST['billing_phone']))
	{
		      $digit_data =preg_replace('/[^0-9]/', '', $_POST['billing_phone']);
			  $countLength=strlen($digit_data);
			  if($countLength<10)
			  {
				  
			  wc_add_notice( 
						sprintf( 'Please enter 10 digit phone number.'
						), 'error' 
					);
			  }
	}
}

/* wrong_code_fundraiser Ajax */
add_action('wp_ajax_nopriv_wrong_code_fundraiser', 'wrong_code_fundraiser'); // for not logged in users
	add_action('wp_ajax_wrong_code_fundraiser', 'wrong_code_fundraiser');
function wrong_code_fundraiser(){
	$ID = $_POST['request_id'];
/*	$query=$ID;
	if ( preg_match( '/([0-9]{6})/', $query) == 1) 
	 { */
		$user_info = get_userdata($ID);
		if(in_array( 'fundraiser', (array) $user_info->roles ))
		{
			$fundr_status = get_user_meta( $ID, 'user_action_status' , true );
			if($fundr_status==2 && !is_user_logged_in())
			{
				$status_code=1; //inactive
			}
			else if(empty($fundr_status) || $fundr_status==1)
			{
				$status_code=2; //active
			}
			else
			{
				
			}
		}
		if(!in_array( 'fundraiser', (array) $user_info->roles ) || $status_code==1)
		{
?>
<style>
.manual-mass-popup
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: block;
	}
.manual-mass-popup .manual-mass-order-popup-wrapper
{
    display: block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
    padding: 40px 25px;
    background: #fff;
    border: 3px solid #000;
    border-radius: 5px;
}
.manual-mass-popup a.fr-btn 
{
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
#manual-mass-popup .show
{
	    background: #fff;
    color: #000;
    margin: 0 auto;
    width: 100%;
    text-align: center;
}
.manual-mass-popup a.fr-btn
{
    display: block;
    margin: 0px auto;
    width: 100%;
    background: none;
    color: #000;
	max-width: 100%;
	display: inline;
    margin: 0px auto;
    width: auto;
	font-size: 25px;
}
#m-link
{
	display: block;
    margin-top: 10px;
}
</style>
 <?php if($status_code==1) { ?>
 <div class="manual-mass-popup">
    <div class="manual-mass-order-popup-wrapper">
	<h3>Error!!</h3>
    <span class="f_msg">Fundrasier is Inactive.</span>
		<a href="#" id="m-link" class="txt-link">Cancel</a>
    </div>
</div>
 
 <?php } else { ?>
<div class="manual-mass-popup">
    <div class="manual-mass-order-popup-wrapper">
	<h3>Error!!</h3>
    <span class="f_msg">Invaild Fundrasier Code.</span>
		<a href="#" id="m-link" class="txt-link">Cancel</a>
    </div>
</div>
<?php 
	} 
		}
		else if(in_array( 'fundraiser', (array) $user_info->roles ) && $status_code==2)
		{
// 			$group_name=get_user_meta($ID, "user_registration_input_company", true);
            $group_name=get_user_meta($ID, "user_registration_name_of_school_business", true);
		?>
<style>
.manual-mass-popup
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: block;
	}
.manual-mass-popup .manual-mass-order-popup-wrapper
{
    display: block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
    padding: 40px 25px;
    background: #fff;
    border: 3px solid #000;
    border-radius: 5px;
}
.manual-mass-popup a.fr-btn 
{
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
#manual-mass-popup .show
{
	    background: #fff;
    color: #000;
    margin: 0 auto;
    width: 100%;
    text-align: center;
}
.manual-mass-popup a.fr-btn
{
    display: block;
    margin: 0px auto;
    width: 100%;
    background: none;
    color: #000;
	max-width: 100%;
	display: inline;
    margin: 0px auto;
    width: auto;
	font-size: 25px;
}
#m-link
{
   font-weight: 700;
    background: #000;
    padding: 10px 49px;
    font-size: 15px;
    letter-spacing: 0.6px;
    display: inline-block;
    margin-top: 15px;
    color: #fff;
    border: 1px solid #000;
    border-radius: 30px;
}
a#m-link:hover {
    background: #fff;
    color: #000;
    border: 1px solid #000;
    border-radius: 30px;
}
#m-link-yes
{
    display: inline-block;
    margin-top: 10px;
    margin-right: 10px;
    background: rgb(197 187 187 / 73%);
    padding: 6px 10px;
    font-size: 14px;
    letter-spacing: 0.6px;
    text-align: center;
    text-decoration: none !important;
	display:none;
}
.manual-mass-popup .f_msg
{
	letter-spacing: 0.4px;
	font-size: 16px;
}
.manual-mass-popup small
{
	    display: block;
    font-size: 15px;
    margin-bottom: 8px;
}
.manual-mass-popup h3
{
	    margin-bottom: 5px;
}
.manual-mass-popup .linecode
{
	    margin-bottom: 6px;
    font-size: 19px;
    color: #000;
}
</style>
<div class="manual-mass-popup">
        <div class="manual-mass-order-popup-wrapper">
		
		<h3><i class="fa fa-check-circle-o" aria-hidden="true" style="font-size: 100px;"></i></h3>
		<p class="linecode">Please confirm. Are you sure?</p>
     <span class="f_msg"><b style="font-size: 16px;">Fundrasier ID:</b> <?php echo "#".$ID; ?></span><br/>
	   <span class="f_msg"><b style="font-size: 16px;">Group Name:</b> <?php echo $group_name; ?></span><br/>
	        <a href="#" id="m-link-yes" class="txt-link">Cancel</a>
			 <a href="#" id="m-link" class="txt-link">Okay</a>
			
        </div>
    </div>
		<?php		
		}
		else
		{
			$action="fundrasier";
			 wp_send_json_success($action);
		}
	/* } */
}

add_action( 'woocommerce_email_after_order_table', 'woocommerce_email_after_order_table_func' );
function woocommerce_email_after_order_table_func( $order ) {
	 $id=get_post_meta( $order->id, 'fundraiser_field', true );
// 	 $group_name=get_user_meta( $id, 'user_registration_input_company', true );
$group_name=get_user_meta( $id, 'user_registration_name_of_school_business', true );
	 $sellerid=get_post_meta( $order->id, 'wc_seller_field', true );
	 $orderType=get_post_meta( $order->id, 'wc_order_type', true );
	if($orderType=="Catlog")
	{
		  if($sellerid=="custom")
		 {
			 $full_name=get_post_meta( $order->id, 'wc_seller_field_custom', true );
		 }
		 else if(is_numeric($sellerid))
		 {
		 $user_info = get_userdata($sellerid);
		 $first_name = $user_info->first_name;
		 $last_name = $user_info->last_name;
		 $full_name= $first_name." ". $last_name;
		 }
		 else
		 {
			 $full_name= "";
		 }
	}
	else
	{
		 if($sellerid=="custom")
		 {
			 $full_name=get_post_meta( $order->id, 'wc_seller_field_custom', true );
		 }
		 else if(is_numeric($sellerid))
		 {
		 $user_info = get_userdata($sellerid);
		 $first_name = $user_info->first_name;
		 $last_name = $user_info->last_name;
		 $full_name= $first_name." ". $last_name;
		 }
		 else
		 {
			 $full_name= $sellerid;
		 }
	}
	  $checkbox=get_post_meta( $order->id, 'wc_checkbox_field', true );
	?>

	<h3>Additional information</h3>
	<table>
		<tr>
			<td>Are you purchasing to support a fundraiser?:</td>
			<td><?php echo wptexturize( get_post_meta( $order->id, 'wc_checkbox_field', true ) ); ?> </td>
			
		</tr>
		<?php if($checkbox=="Yes") { ?>
		<tr>
			<td>Fundraiser ID: </td>
			<td><?php echo wptexturize( get_post_meta( $order->id, 'fundraiser_field', true ) ); ?></td>
		</tr>
		<?php if(!empty($full_name)){?>
		<tr>
			<td>Seller Name: </td>
			<td><?php echo wptexturize( $full_name ); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Group Name: </td>
			<td><?php echo wptexturize( $group_name ); ?></td>
		</tr>
		<?php } ?>
	</table>

	<?php
}
add_action( 'template_redirect', 'redirect_to_specific_page' );

function redirect_to_specific_page() {

if ( is_page('fundraiser-reports') && ! is_user_logged_in() ) {

wp_redirect( home_url('/my-account'), 302 );
  //exit;
    }
	if ( is_page('seller-reports') && ! is_user_logged_in() ) {

wp_redirect( home_url('/my-account'), 302 );
  //exit;
    }
	
	if ( is_page('seller-dashboard') && ! is_user_logged_in() ) {

wp_redirect( home_url('/my-account'), 302 );
  //exit;
    }
}
add_filter( 'bulk_actions-edit-shop_order', 'misha_register_bulk_action' ); // edit-shop_order is the screen ID of the orders page
 
function misha_register_bulk_action( $bulk_actions ) {
 
	$bulk_actions['mark_cancelled'] = 'Cancelled Order'; // <option value="mark_awaiting_shipment">Mark awaiting shipment</option>
	return $bulk_actions;
 
}

add_action( 'woocommerce_applied_coupon', 'apply_matched_coupons' );

function apply_matched_coupons() {
    global $woocommerce;

    $coupon_code = 'Friends25'; // coupon code
	$coupon_code2 = 'glow50';
	$applied_coupon=$_POST['coupon_code'];
	$coupon = new WC_Coupon( $applied_coupon );
	$id=$coupon->get_id();
	
	$no_fundraiser= get_post_meta($id, 'coupon_non_fundraiser', true);
	if($no_fundraiser=="Yes"){
	/*if ( $woocommerce->cart->has_discount( $coupon_code ) )
	{*/
		/*if(is_cart()) 
		{ */
			$user = wp_get_current_user();
			if(!empty($_SESSION["fridID"]) || in_array( 'fundraiser', (array) $user->roles ) || in_array( 'seller', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles ) )
			{
				$woocommerce->cart->remove_coupons( sanitize_text_field( $applied_coupon) );
				
				wc_clear_notices();
				
				 wc_add_notice( 
					sprintf( 'Coupon not valid for fundraising order' 
					), 'error' 
				);
				
			}
		
	}
	else
	{
		$user = wp_get_current_user();
		if(in_array( 'fundraiser', (array) $user->roles )  )
			{
				if( !empty($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog'){
				$woocommerce->cart->remove_coupons( sanitize_text_field( $applied_coupon) );
				
				wc_clear_notices();
				
				 wc_add_notice( 
					sprintf( 'Coupon not valid for fundraising order' 
					), 'error' 
				);
				}
			}
	}
	/*if ( $woocommerce->cart->has_discount( $coupon_code2 ) )
	{
		
			$user = wp_get_current_user();
			if(!empty($_SESSION["fridID"]) || in_array( 'fundraiser', (array) $user->roles ) || in_array( 'seller', (array) $user->roles ) )
			{
				
				$woocommerce->cart->remove_coupons( sanitize_text_field( $coupon_code2) );
				wc_clear_notices();
				
				 wc_add_notice( 
					sprintf( 'Coupon not valid for fundraising order' 
					), 'error' 
				);
				
			}
		
	}*/
			
		
		
	}
	
	/* add option in coupon section */
	add_action( 'woocommerce_coupon_options_usage_restriction', 'add_coupon_option', 10, 0 );
	function add_coupon_option() 
	{
		global $post;
		$id = get_the_ID();
		if( $id ) 
		{
			$coupon_option = get_post_meta($id, 'coupon_non_fundraiser', true);
		}
		?>
		<p class="form-field individual_use_field ">
		<label for="individual_use">Coupon for non-fundraiser order?</label><input type="radio" id="acf-field_5f378fbf61733" name="coupon_option_non_fund" value="Yes" <?php if($coupon_option=="Yes"){ ?>checked="checked" <?php } ?>> Yes  <input type="radio" id="acf-field_5f378fbf61733-No" name="coupon_option_non_fund" value="No" <?php if($coupon_option=="No"){ ?>checked="checked" <?php } ?>> No</p>
		<?php
	}
	/* save option data */
	add_action( 'woocommerce_coupon_options_save', 'save_coupon_option');
	function save_coupon_option($post_id)
	{
		if( isset($_POST['coupon_option_non_fund']) ) 
		{
			$post_data = $_POST['coupon_option_non_fund'];
			update_post_meta( $post_id, 'coupon_non_fundraiser', $post_data );
		}
	}
	/* Alert on checkout for fundraiser is not found */
	add_action('woocommerce_before_checkout_form','fundraiser_not_found');
	function fundraiser_not_found()
	{
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }
		$fID = isset( $_SESSION["fridID"] ) ? $_SESSION["fridID"] : ( function_exists('WC') && WC()->session ? WC()->session->get('fridID') : '' );
		if ( ! empty( $fID ) ) 
		{ 
			$user_info = get_userdata( $fID );
			$email = ( $user_info && is_object( $user_info ) ) ? $user_info->user_email : '';
			if ( empty( $email ) ) 
			{
			?>
				<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
					<ul class="woocommerce-error" role="alert">
						<li><?php echo esc_html( $fID . " FUNDRAISER NOT FOUND" ); ?></li>
					</ul>
				</div>
			<?php
			}
		}
	}

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    .wc-pending{display:none !important;
    } 
  </style>';
}

/* Close Out email Confirmation */ 
function wp_close_out_send_email( $email)
	{
		$to = $email;
		$fid = strval($_POST['f_id']);
		//$register_link = 'https://glowbrandscandles.com/seller-register/?frid='.$f_id;
		//$link= "<a href=".$register_link.">Seller Registration</a>";
		$subject = "Close Out request Successfully Sent!";
		$body ='';
	ob_start();
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 700px;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Close Out Fundraiser Request </h1>
		  	</td>
		</tr>
		 <tr>
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
								<tr>
									<td align="left">
										<p style="font:17px/23px  PTSansRegular , Arial, Helvetica, sans-serif;color:#1b0f0e;">Hi </p>
										<p  style="font:17px/23px PTSansRegular, Arial, Helvetica, sans-serif;color:#1b0f0e;">We have  received your close out request. we will work on this in next 1-3 business days. </p>
										
										<p style="font:17px/23px  PTSansRegular , Arial, Helvetica, sans-serif;color:#1b0f0e;">Thanks! </p>
								</td>
			
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
		/*$headers = "From: Glow Scented Candles <info@glowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	/*if($mailSuc){
		echo "Your message has been successfully sent.";
	} else{
		echo "Error!!";
	}*/
	die();
		
	
		
	}

/* Close Out Fundraisering Ajax */
add_action('wp_ajax_nopriv_close_out_fundraiser', 'close_out_fundraiser'); // for not logged in users
	add_action('wp_ajax_close_out_fundraiser', 'close_out_fundraiser');
	function close_out_fundraiser( )
	{
		$ID = $_POST['fund_id'];
		$RID = $_POST['reps_id'];
	//	$to = "jatinarora0075@gmail.com";
		date_default_timezone_set('America/Chicago');
		/*$current_time = date('Y-m-d H:i:s');	*/				 
		$current_date = date('Y-m-d H:i:s a');
		$args=array('post_title'=>'Close Out #'.$ID, 'post_type'=>'close_out', 'post_status' => 'publish', 'post_author' => $ID);
	    $post_id = wp_insert_post($args,  true);
	
		if(!empty($post_id))
	    {
			if(!empty($RID))
			{
				update_post_meta( $post_id, 'close out_by_rep_id', $RID);
			}
			update_post_meta( $post_id , 'close out_fundraiser_id', $ID);
		    update_post_meta( $post_id , 'close out_fundraiser_date', $current_date); 
			update_user_meta($ID, "user_close_out_value", 1);
			update_user_meta($ID, "user_close_out_date_inactive", $current_date);
			update_user_meta($ID, 'user_action_status' , 2 );
		}
		
// 		$org=get_user_meta( $ID , 'user_registration_input_company' , true );
$org=get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
		if(empty($org))
{
$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
}
		$f=get_user_meta( $ID , 'first_name' , true );		
		$l=get_user_meta( $ID , 'last_name' , true );	
		$user_info = get_userdata($ID);
        $email = $user_info->user_email;
        $to = "candace@glowscentedcandles.com";  
	/*    $to = "jatinarora0075@gmail.com";   */
	  //  $to = "shivanideveloper1991@gmail.com"; 
		$subject = "Close Out Fundraiser #".$ID;
		$body ='';
	ob_start();
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Fundraiser Close Out Request <?php echo "#".$ID; ?> </h1>
		  	</td>
		</tr>
			
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							<tr>
								<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Fundraiser ID: <strong><?php echo "#".$ID; ?></strong></td>
							</tr>
							<tr>
								<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Contact Name: <strong><?php  echo $f." ".$l; ?></strong></td>
							</tr>
							<tr>
								<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Contact Email: <strong><?php  echo $email; ?></strong></td>
							</tr>
							<tr>
								<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: capitalize;line-height: 1.3;font-size: 14px;">Organizations Name: <strong><?php  echo $org; ?></strong></td>
							</tr>
							<tr>
								<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: initial;line-height: 1.3;font-size: 14px;">Fundraiser wants to close fundraisering campaign.</td>
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
		/*$headers = "From: Glow Scented Candles <info@glowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	if($mailSuc){
			if(!empty($RID))
			{
				$mergeID=get_user_meta( $ID, 'wc_ship_mergeOrderID', true );
				$mergeQuanty=get_user_meta( $ID, 'wc_ship_mergeOrderQuanty', true );
				if(empty($mergeID) && empty($mergeQuanty)) { 
				 wp_send_json_success(array(
					'action' => "yes",
					'fund_id'=> $ID,
					'msg'=> "Close out request sent."
					));
				}
				else
				{
					 wp_send_json_success(array(
					'action' => "no",
					'fund_id'=> $ID,
					'msg'=> "Close out request sent."
					));
				}
			}
			else {
			echo "Close out request sent.";
			}
		wp_close_out_send_email($email);
	} else{
		echo "Error!!";
	}
	die();
		
	
		
	}
/*
* Creating a function Fundraiser Action
*/
	add_action('wp_ajax_nopriv_fund_action_status', 'fund_action_status'); // for not logged in users
	add_action('wp_ajax_fund_action_status', 'fund_action_status');
	function fund_action_status( )
	{
		$ID = $_POST['a_fund_id'];
		$action = $_POST['a_st_ac'];
		date_default_timezone_set('America/Chicago');
		/*$current_time = date('Y-m-d H:i:s');	*/				 
		$current_date = date('Y-m-d H:i:s');
		
	    if($action==1)
		{
			update_user_meta($ID, "user_close_out_date_active", $current_date);
		}
		if($action==2)
		{
	      /*if($ID=="205821" || $ID=="205989" || $ID=="205931" || $ID=="205874" || $ID=="205974" || $ID="206046")
		   {
			$current_date_s="2020-08-25 1:00:00";	
			update_user_meta( $ID , 'user_close_out_date_inactive' , $current_date_s );
	     	}*/
			update_user_meta($ID, "user_close_out_date_inactive", $current_date);
		}
		update_user_meta( $ID , 'user_action_status' , $action );
		/*echo "Your Status has been successfully updated.";*/
		 wp_send_json_success(array(
		'action' => $action
		));
	
	}
/* Save Order Button on Checkout */
add_action('woocommerce_before_checkout_form','wdm_add_custom_save_btn');
function wdm_add_custom_save_btn()
{
	?>
		<style>
	
	.ship_note_new {
    width: 100%;
    display: inline-block;
    background: #69bb6c;
    padding: 20px 20px;
    color: #767676;
    background-color: #d4edda;
    border-color: #c3e6cb;
    position: relative;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    font-size: 14px;
    font-family: 'Roboto', Georgia, sans-serif;
    letter-spacing: 0.3px;
	    margin-bottom: 30px;
	}
	.ship_note_new span {
    display: block;
}
.ship_note_new .bold-size
{
	    font-weight: 600;
}
@media screen and (max-width: 480px)
{
.ship_note_new {
    width: 100%;
    display: inline-block;
    background: #69bb6c;
    padding: 20px 20px;
    color: #767676;
    background-color: #d4edda;
    border-color: #c3e6cb;
    position: relative;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    font-size: 12px;
    font-family: 'Roboto', Georgia, sans-serif;
    letter-spacing: 0.3px;
    margin-bottom: 13px;
}
}
@media screen and (max-width: 990px)
{
	form.checkout.woocommerce-checkout 
	{
		padding: 0 20px;
	}
}
	</style>
	<div class="ship_note_new"><span>Your privacy and security is of the upmost importance to us.</span>
<span class="bold-size">PLEASE ADVISE-</span>
<span>The billing address and zip code must match your credit card to process your order.
If you receive an error or decline in your transaction your order was not completed successfully.
You will receive a email confirmation when the order is placed. </span>
<span>Thank you for your understanding.</span></div>
	<?php
	if(!empty($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog') 
	{
	?>
	<div class="ship_note_new">
		<span class="bold-size">Fundraiser Brochure Orders must ship to School or Commerical Address.</span>
	</div>	
	<?php		
	}
	
	if(empty($_SESSION['sellername'])) 
	{
		$cart_contents = $woocommerce->cart->get_cart();
		foreach ( $cart_contents as $cart_item_key => $values )
		{
				  $seller_name = $values['wdm_seller_name'];
				  if(empty($seller_name))
				  {
					  $order_typet='Online';
				  }
				  else
				  {
					 $order_typet='Catlog'; 
				  }
		}
		if( $order_typet=='Catlog' ) 
		{
			$user = wp_get_current_user();
			if( in_array( 'fundraiser', (array) $user->roles ) ) 
			{
				if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }
				$_SESSION['sellername']='Catlog';
			}
		}
	}
if(!empty($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog') 
{
	 ob_start();	
  if (is_user_logged_in())
  {
	  $user = wp_get_current_user();
		if ( in_array( 'fundraiser', (array) $user->roles ) ) {
?>
<style>
.wc_payment_methods .wc_payment_method.payment_method_cheque
{
	display:block;
}
</style>
		<?php } } ?>
<style>
.show_load_save{
	display:inline-block;
}
.hide_load_save{
	display:none;
}
#save_order
{
	text-transform: uppercase; 
	background-color: #cda85c; 
	color: #fff; padding: 10px 24px;
	font-weight: 600;
	border-radius:30px;
	margin-left: 25px;
	 transition: all 0.5s ease 0s;
	     background-image:linear-gradient(to right, #cda85c 0%, #efd589 100%, #cda85c 100%);
}
#save_order:hover{
	background-position: right center;
	/*	background-color: #fff; 
		color:#cda85c;
		border:1px solid #cda85c;*/
}
@media screen and (max-width: 558px)
{
	#save_order
	{
		position: relative;
		display: inline-block;
		margin-top: 8px;
		padding: 6px 20px;
		font-size: 11px;
		margin-left: 0;
	}
}
</style>

<div class="woocommerce-info save-btn">
	If you want to pay later then you can save order.<a id="save_order" style="">Save Order and Pay Later</a>
<img id="loading_s" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" class="hide_load_save">
</div>

<script>
jQuery('#save_order').click(function(){
jQuery("#loading_s").removeClass("hide_load_save");
				jQuery("#loading_s").addClass("show_load_save");
				var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'wdm_save_cart_details'},

         success: function(response) {
				jQuery("#loading_s").removeClass("show_load_save");
				jQuery("#loading_s").addClass("hide_load_save");
        console.log(response);
		jQuery(".save-btn").append('<p style="padding-top:0px;color:green;margin:0;margin-left: 15px;display: inline-block;">Catalogue Order Saved Successfully!!</p>');
    }
});

});
</script>
<?php

    $content = ob_get_contents();
    ob_end_flush();

    return $content;
	}
}

/* Save Cart Data to meta */
add_action('wp_ajax_nopriv_wdm_save_cart_details', 'wdm_save_cart_details'); // for not logged in users
add_action('wp_ajax_wdm_save_cart_details', 'wdm_save_cart_details');
function wdm_save_cart_details()
{
  global $woocommerce;

  // get user details
  global $current_user;
  $current_user = wp_get_current_user();

  if (is_user_logged_in())
  {
    $user_id = $current_user->ID;
    $cart_contents = $woocommerce->cart->get_cart();
    $meta_key = 'cart-restore';
    $meta_value = $cart_contents;
    update_user_meta( $user_id, $meta_key, $meta_value);
	echo "Catalogue Order Saved Successfully!!";
  }
 
}
/* Restore Cart Data from meta */
add_action('wp_ajax_nopriv_wdm_restore_cart_details', 'wdm_restore_cart_details'); 
add_action('wp_ajax_wdm_restore_cart_details', 'wdm_restore_cart_details');
function wdm_restore_cart_details()
{
	 global $woocommerce;
	 global $current_user;
	$current_user = wp_get_current_user();

	if (is_user_logged_in())
	{
		
		$user_id = $current_user->ID;
		$wdm_selected_cart_key='cart-restore';
		/*$cart_content = get_user_meta($user_id, $wdm_selected_cart_key, true);*/
		$cart_content = get_user_meta($user_id, 'cart-restore', true);
		  // clear current cart, incase you want to replace cart contents, else skip this step
		 $woocommerce->cart->empty_cart();
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }
		$_SESSION['sellername']='Catlog';
		  // add cart contents
		  foreach ( $cart_content as $cart_item_key => $values )
		  {
			  $custom_data=array();
			$id = $values['product_id'];
			$quant = $values['quantity'];
			$seller_name = $values['wdm_seller_name'];
			$custom_data['wdm_seller_name'] = $seller_name;
			
			$woocommerce->cart->add_to_cart( $id, $quant, NULL, NULL, array('wdm_seller_name'=>$seller_name,'wdm_seller_display'=>$seller_name,'restore_saved_order'=>'Yes'));
			
		  }
		  echo "Saved Order Restore Successfully!!";
	}
}

/*add_action( 'woocommerce_after_checkout_form', 'glow_add_css_checkout');
function glow_add_css_checkout()
{
	if(!empty($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog') 
	{
	?>
		<style>
				#wc_seller_field_field
				{
					display:none !important;
				}
				</style>
	<?php
	}
}*/
/* Ajax Participant List */
add_action('wp_ajax_nopriv_wdm_participant_list', 'wdm_participant_list'); 
add_action('wp_ajax_wdm_participant_list', 'wdm_participant_list');
function wdm_participant_list()
{
	$ID = $_POST['fund_id'];
	$wp_body ='';
	ob_start();
	$all_meta_for_users = get_users(array(
                           'meta_key'     => 'user_registration_seller_fundraiser',
                           'meta_value'   => $ID,
                          ));
	?>
	 <?php 
			$full_arr_t=array();
			$c=0;
			$total_cat_quantity =0; 						
			
			/*Ship to School - Catalog qty */
			 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
			/* End */
					
	        foreach ( $all_meta_for_users as $all_meta_for_user ) { 
							$total_sale_so_far=0;
							$rebate_so_far=0;
							$item_qty_fund_online=0;
							$item_qty =0;
							$IDD=$all_meta_for_user->ID; 
						    $seller_first=$all_meta_for_user->user_firstname;
						    $seller_last=$all_meta_for_user->user_lastname;
                            $args = array(
                                'status'   => array('wc-waiting-call','completed','processing'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
								
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								/* 
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
								*/
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
									foreach ($order->get_items() as $item_key => $item_values): 
                                        $item_data = $item_values->get_data(); 
										$item_qty  +=  $item_data['quantity']; 
									endforeach; 
									
								  if ($frID3 != $order_customer_id) 
								  { 
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										if ($order_type=='ship-to-school') {
											
											/*$total_sale_so_far+=$order->get_subtotal();*/
													$tdiscount=$order->get_total_discount();
													
													if(!empty($tdiscount))
													{
													  $newamt=$order->get_subtotal()-$tdiscount;
													  $total_sale_so_far+=$newamt;
													}
													else
													{
													 $total_sale_so_far+=$order->get_subtotal();
													}
													$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

													foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
																		if($order_type_2=="ship-to-school") 
																		{
																			$item_qty_fund_online +=  $item_data['quantity']; 
																		}
																		
																		else{}
														 endforeach; 
												
											$percentage  = 40;
											$percentFifty= 50;
											$real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
											if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
											{
												$Discount  = ($percentFifty / 100) * $total_sale_so_far;
												$rebate_so_far = number_format($Discount, 2, '.', '');
												
											}
											else
											{
												$Discount  = ($percentage / 100) * $total_sale_so_far;
												$rebate_so_far = number_format($Discount, 2, '.', '');
												
											}				
														 
									 }
									  else if($order_type=="Online") 
									 {
										
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
								  
								 }
								 else
								 {
									 
								 }
								} 
								else if ($frID3 == $order_customer_id) 
								{ 
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
								}
								}
								else{}
								
								} 
								
								/* } */
								
								}
						}
						
						
						
						$full_arr_t[$c]['item_qty']=$item_qty;
						$full_arr_t[$c]['id']=$IDD;
						$full_arr_t[$c]['total_sale']=number_format($total_sale_so_far, 2, '.', '');
						$full_arr_t[$c]['rebate']=number_format($rebate_so_far, 2, '.', '');
						/*$full_arr_t[$c]['seller_name']=$all_meta_for_user->first_name." ".$all_meta_for_user->last_name;*/
						$full_arr_t[$c]['seller_name']=$seller_first." ".$seller_last;
						$c++;
			}
				$col_total_sale = array_column($full_arr_t, 'total_sale');

						array_multisort($col_total_sale, SORT_DESC, $full_arr_t);
						/*print_r($full_arr_t);*/
				for($j=0; $j<count($full_arr_t);$j++) { 
	 ?>
                              <div id="<?php echo $j; ?>" class="item style-scope event-payout">
                                 <span class="label style-scope event-payout" style="text-transform: capitalize;" data-seller="<?php echo $full_arr_t[$j]['seller_name']; ?>"><?php echo $full_arr_t[$j]['seller_name']; ?></span>
                                 <span class="value style-scope event-payout">
                                  <?php echo "$".$total_sale_so_far_registered=number_format($full_arr_t[$j]['total_sale'], 2, '.', ''); ?> |
                                 <span class="highlight-green style-scope event-payout"><?php echo "$".$rebate_so_far_registered=number_format($full_arr_t[$j]['rebate'], 2, '.', ''); ?></span> | <span class="value style-scope event-payout"><?php echo $full_arr_t[$j]['item_qty']; ?></span>
                                 </span>
                              </div>
							 <?php } ?>
	<?php
	$wp_body = ob_get_contents();
	ob_end_flush();
	die();
}
/* Ajax Participant Manually Added Seller List */
add_action('wp_ajax_nopriv_wdm_participant_list_manually', 'wdm_participant_list_manually'); 
add_action('wp_ajax_wdm_participant_list_manually', 'wdm_participant_list_manually');
function wdm_participant_list_manually()
{
	$ID = $_POST['fund_id'];
	$wp_body ='';
	ob_start();
		/*Ship to School - Catalog qty */
			 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
			/* End */
     $args = array(
                                'status'   => array('wc-waiting-call','completed','processing'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								/*	if ( $order->has_status('completed') || $order->has_status('processing')) 
									{ */
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									}
									} 
									else if($order_type=="ship-to-school") 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									}
									}
									else{}									
								/*	} */
							}
						}
						$full_arr_t=array();
						/* Calculate seller Total*/
							$args = array(
                                 'status'  => array('wc-waiting-call','completed','processing'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count($seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
							$item_qty=0;
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								/*	if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
									*/
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
									
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$item_qty  +=  $item_data['quantity'];
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									} } 
									else if($order_type=="ship-to-school") 
									{
										/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
									
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$item_qty  +=  $item_data['quantity'];
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
													   foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
																		if($order_type_2=="ship-to-school") 
																		{
																			$item_qty_fund_online +=  $item_data['quantity']; 
																		}
																		
																		else{}
														 endforeach; 
														
														$real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
														if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
														{
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														}
														else
														{
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														}
														
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									} }
									else{}		
									
								/*	} */
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						$full_arr_t[$i]['item_qty']=$item_qty;
						$full_arr_t[$i]['total_sale']=number_format($seller_total_sale, 2, '.', '');
						$full_arr_t[$i]['rebate']=number_format($seller_rebate, 2, '.', '');
						$full_arr_t[$i]['seller_name']=$seller_name[$i];
						}
						$col_total_sale = array_column($full_arr_t, 'total_sale');

						array_multisort($col_total_sale, SORT_DESC, $full_arr_t);
	?>
	 <?php 
	     $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							/*for($i=0;$i<count($seller_name);$i++)*/
							for($j=0; $j<count($full_arr_t);$j++) 
							{  
	 ?>
                              <div id="<?php echo $j; ?>" class="item style-scope event-payout">
                                 <span class="label style-scope event-payout" style="text-transform: capitalize;"><?php echo $full_arr_t[$j]['seller_name']; ?></span>
                                 <span class="value style-scope event-payout">
                                  <?php echo "$".$seller_t=number_format($full_arr_t[$j]['total_sale'], 2, '.', ''); ?> |
                                 <span class="highlight-green style-scope event-payout"><?php echo "$".$seller_r=number_format($full_arr_t[$j]['rebate'], 2, '.', ''); ?></span> | <span class="value style-scope event-payout"><?php echo $full_arr_t[$j]['item_qty']; ?></span>
                                 </span>
                              </div>
							 <?php } ?>
	<?php
	$wp_body = ob_get_contents();
	ob_end_flush();
	die();
}
/*
add_action( 'woocommerce_email_order_details', 'mm_email_footer', 10, 1 );
function mm_email_footer( $email ) { ?>
	<p style="text-align: center;margin: 15px auto;width: 100%;"><img style="width: 200px;" src="https://glowscentedcandles.com/wp-content/themes/glowscentedcandles/images/FINAL_2_Logo_Glow_2_300x300_transperant_40.png"></p>

<?php
}*/
/********* AJAX Login ***********/
function yourtheme_ajax_login_init(){
    wp_register_script('ajax-login-script', get_stylesheet_directory_uri() . '/js/ajax-login-script.js', array('jquery') );
    wp_enqueue_script('ajax-login-script');
    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'loadingmessage' => esc_html__('Sending user info, please wait...', 'yourtheme')
    ));
    // Enable the user with no privileges to run ajax_login() in AJAX
    
}
// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'yourtheme_ajax_login_init');
}
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
if (!function_exists('ajax_login')) {
    function ajax_login(){
        // First check the nonce, if it fails the function will break
        check_ajax_referer( 'ajax-login-nonce', 'security' );
        // Nonce is checked, get the POST data and sign user on
        $info = array();
        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = true;
        $user_signon = wp_signon( $info, false );

        if ( is_wp_error($user_signon) ){
            echo json_encode(array('loggedin'=>false, 'message'=> esc_html__('Wrong username or password.', 'yourtheme')));
        } else {
			if(in_array( 'representative', (array) $user_signon->roles ) && in_array( 'fundraiser', (array) $user_signon->roles )){
				$redirect_url=("" . glow_dynamic_url_base('home') . "my-dashboard/");
			}else if( in_array( 'representative', (array) $user_signon->roles ) && in_array( 'seller', (array) $user_signon->roles )){
				$redirect_url=("" . glow_dynamic_url_base('home') . "my-dashboard/");
			} else{
				if ( in_array( 'fundraiser', (array) $user_signon->roles ) ) {
					$redirect_url=("" . glow_dynamic_url_base('home') . "fundraiser-dashboard/");
				}
				
				else if(in_array( 'representative', (array) $user_signon->roles ) ){
					$redirect_url=("" . glow_dynamic_url_base('home') . "representative-dashboard/");
				}
				else if ( in_array( 'seller', (array) $user_signon->roles ) ) {
					$redirect_url=("" . glow_dynamic_url_base('home') . "seller-dashboard/");
				}
				else if ( in_array( 'administrator', (array) $user_signon->roles ) ) {
					$redirect_url="<?php echo admin_url(); ?>";
				}
				else {
					$redirect_url=("" . glow_dynamic_url_base('home') . "my-account/");
				}
			}
            echo json_encode(array('loggedin'=>true, 'test'=>$user_signon, 'url'=> $redirect_url, 'message'=> esc_html__('Login successful, redirecting...', 'yourtheme')));
        }
        wp_die();
    }
}
add_action( 'sendy_scheduled_cron', 'sendy_scheduled' );
function sendy_scheduled()
{ 
file_get_contents(('' . glow_dynamic_url_base('home') . 'newsletter/scheduled.php'));
}
function disable_coupon_field_on_cart( $enabled ) {
	$user = wp_get_current_user();
	if(in_array( 'fundraiser', (array) $user->roles ))
	{
		if ( is_cart() || is_checkout()) {
			$enabled = false;
		}
		return $enabled;
	}
	else{
			return true;
	}
}
add_filter( 'woocommerce_coupons_enabled', 'disable_coupon_field_on_cart' );
//add_filter( 'default_checkout_state', 'change_default_checkout_state' );
function change_default_checkout_state() {
  return 'AL'; // state code
}
add_filter( 'default_checkout_billing_country', 'bbloomer_change_default_checkout_country' );
 
function bbloomer_change_default_checkout_country() {
  return 'US'; 
}

/* profile code*/ 
function wpd_foo_rewrite_rule() 
{
	  global $wp_rewrite;
    /*add_rewrite_tag('%frid%', '([^&]+)');*/
    add_rewrite_rule(
        'profile/([a-z0-9-]+)[/]?$',
        'index.php?profile=$matches[1]',
        'top'
    );
}
add_action( 'init', 'wpd_foo_rewrite_rule' ,10, 0);

/*Seller Code*/
function wpd_foo_rewrite_rule2() {
	  global $wp_rewrite;
    /*add_rewrite_tag('%frid%', '([^&]+)');*/
   
	add_rewrite_rule(
        'seller/([a-z0-9-]+)[/]?$',
        'index.php?seller=$matches[1]',
        'top'
    );
}
add_action( 'init', 'wpd_foo_rewrite_rule2' ,10, 0);

function wpd_foo_rewrite_rule3() {
	  global $wp_rewrite;
    /*add_rewrite_tag('%frid%', '([^&]+)');*/
   
	add_rewrite_rule(
        'seller/user/([a-zA-Z0-9-_.]+)[/]?$',
        'index.php?user=$matches[1]',
        'top'
    );
}
add_action( 'init', 'wpd_foo_rewrite_rule3' ,10, 0);

add_filter( 'query_vars', function( $query_vars ) {
    $query_vars[] = 'seller';
    return $query_vars;
} );
add_filter( 'query_vars', function( $query_vars ) 
{
    $query_vars[] = 'profile';
    return $query_vars;
} );
add_filter( 'query_vars', function( $query_vars ) {
    $query_vars[] = 'user';
    return $query_vars;
} );
add_filter( 'template_include', function( $template ) 
{
	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
	if ( get_query_var( 'profile' ) || preg_match( '#/profile/[a-zA-Z0-9_-]+#i', $req_uri ) )
	{
		$plugin_tpl = dirname( __FILE__ ) . '/templates/template-profile.php';
		if ( file_exists( $plugin_tpl ) ) {
			return $plugin_tpl;
		}
		if ( file_exists( get_stylesheet_directory() . '/template-profile.php' ) ) {
			return get_stylesheet_directory() . '/template-profile.php';
		}
		return get_template_directory() . '/template-profile.php';
	}
	else if ( get_query_var( 'seller' ) || get_query_var( 'user' ) || preg_match( '#/seller/[a-zA-Z0-9_.-]+#i', $req_uri ) )
	{
		$plugin_tpl = dirname( __FILE__ ) . '/templates/template-sellerprofile.php';
		if ( file_exists( $plugin_tpl ) ) {
			return $plugin_tpl;
		}
		if ( file_exists( get_stylesheet_directory() . '/template-sellerprofile.php' ) ) {
			return get_stylesheet_directory() . '/template-sellerprofile.php';
		}
		return get_template_directory() . '/template-sellerprofile.php';
	}
	else{
		 return $template;
	}
} );
function wpb_woo_endpoint_title( $title, $id ) 
{
	if ( get_query_var( 'profile' ))
	{
		$IDG=get_query_var( 'profile' );
		$f=get_user_meta( $IDG , 'first_name' , true );		
		$l=get_user_meta( $IDG , 'last_name' , true );	
// 		$org=get_user_meta( $IDG , 'user_registration_input_company' , true );
$org=get_user_meta( $IDG , 'user_registration_name_of_school_business' , true );
		$title=$org.' | Glow Scented Candles';
		return $title;
	}
	return $title;
}
add_filter( 'wpseo_title', 'wpb_woo_endpoint_title', 10000, 2 );
function wpb_woo_endpoint_title2( $title, $id ) {
	 if ( get_query_var( 'seller' ))
	{
		$IDG=get_query_var( 'seller' );
		$f=get_user_meta( $IDG , 'first_name' , true );		
		$l=get_user_meta( $IDG , 'last_name' , true );	
		$org=$f." ".$l." | Support Now | Fund Raising";
		$title=$org;
		return $title;
	}
	
	return $title;
}
	
add_filter( 'wpseo_title', 'wpb_woo_endpoint_title2', 10000, 2 );
function wpb_woo_endpoint_title3( $title, $id ) {
	 if ( get_query_var( 'user' ))
	{
		$query = get_query_var( 'user' );
		$user = get_user_by( 'login', $query );
		if($user)
		{
			$IDG=$user->ID;
			$f=get_user_meta( $IDG , 'first_name' , true );		
			$l=get_user_meta( $IDG , 'last_name' , true );	
			$org=$f." ".$l;
			$title=$org;
			return $title;
		}
	}
	
	return $title;
}
add_filter( 'wpseo_title', 'wpb_woo_endpoint_title3', 10000, 2 );

function disable_yoast_schema_data($data){
	if ( get_query_var( 'profile' ))
	{
	$data = array();
	return $data;
	}
	else if ( get_query_var( 'seller' ))
	{
	$data = array();
	return $data;
	}
	else if ( get_query_var( 'user' ))
	{
	$data = array();
	return $data;
	}
	else{}
}
add_filter('wpseo_json_ld_output', 'disable_yoast_schema_data', 10, 1);

function joe_remove_yoast_meta ($filter) {
  // Add your conditions here, in case you want to remove Meta Robots from the Archive Template
if ( get_query_var( 'profile' ))
{
    return false;
 }
 if ( get_query_var( 'seller' ))
{
    return false;
 }
 if ( get_query_var( 'user' ))
{
    return false;
 }
  return $filter;
}
add_filter( 'wpseo_canonical', 'joe_remove_yoast_meta' );
add_filter( 'wpseo_metadesc', 'joe_remove_yoast_meta' );
add_filter( 'wpseo_opengraph_url', 'joe_remove_yoast_meta' );
add_filter( 'wpseo_opengraph_type', 'joe_remove_yoast_meta' );
add_filter( 'wpseo_opengraph_title', 'joe_remove_yoast_meta' );
add_filter( 'wpseo_opengraph_site_name', 'joe_remove_yoast_meta' );




/* Ajax for auto fundraiser code */
add_action('wp_ajax_nopriv_wdm_auto_fundraiser_code', 'wdm_auto_fundraiser_code'); 
add_action('wp_ajax_wdm_auto_fundraiser_code', 'wdm_auto_fundraiser_code');
function wdm_auto_fundraiser_code()
{
	$fund_code=$_POST['fund_id'];
	//if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }
	$_SESSION["fridID"] = $fund_code;
	
}
/* Ajax for auto fundraiser code and Seller Name */
add_action('wp_ajax_nopriv_wdm_auto_fundraiser_code2', 'wdm_auto_fundraiser_code2'); 
add_action('wp_ajax_wdm_auto_fundraiser_code2', 'wdm_auto_fundraiser_code2');
function wdm_auto_fundraiser_code2()
{
	$fund_code2=$_POST['fund_id2'];
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }
	$_SESSION["fridID"] = $fund_code2;
	$seller_code=$_POST['seller_id'];
	$f=get_user_meta( $seller_code , 'first_name' , true );		
	$l=get_user_meta( $seller_code , 'last_name' , true );	

	$_SESSION["seller_name"] = $f." ".$l;
	
}
/**
 * Disable default WordPress emails.
 */
/*add_filter( 'send_password_change_email', '__return_false' );*/
add_filter( 'send_email_change_email', '__return_false' );

/* Ajax for auto Take Tour- Skip Button */
add_action('wp_ajax_nopriv_wdm_welcome_pop_up_skip', 'wdm_welcome_pop_up_skip'); 
add_action('wp_ajax_wdm_welcome_pop_up_skip', 'wdm_welcome_pop_up_skip');
function wdm_welcome_pop_up_skip()
{
	$user_id=$_POST['skip_popup_user_id'];
	if(!empty($_POST['skip_popup_user_id']) && !empty($_POST['skip_popup']))
	{
		$skip_popup=$_POST['skip_popup'];
		update_user_meta($user_id, "skip_popup", $skip_popup);
		$action="Sucessfully Updated";
	}
	else if(!empty($_POST['skip_popup_user_id']) && !empty($_POST['skip_popup_FD']))
	{
		$skip_popup_FD=$_POST['skip_popup_FD'];
		update_user_meta($user_id, "skip_popup_FD", $skip_popup_FD);
		$action="Sucessfully Updated";
	}
	else{
		$action="Error!!";
	}
	 wp_send_json_success(array(
		'action' => $action
		));
}
add_action( 'wp_ajax_file_upload', 'file_upload_callback' );
add_action( 'wp_ajax_nopriv_file_upload', 'file_upload_callback' );
function file_upload_callback() {
   if (!function_exists('wp_generate_attachment_metadata')){
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }
         if ($_FILES) {
            foreach ($_FILES as $file => $array) {
                if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                    return "upload error : " . $_FILES[$file]['error'];
                }
                $attach_id = media_handle_upload( $file, $u_id );
            }   
        }
        if ($attach_id > 0){
            //and if you want to set that image as Post  then use:
			//$link = get_permalink( $attach_id );
			$image_attributes = wp_get_attachment_url( $attach_id );

           /*echo $image_attributes;*/
		    wp_send_json_success($image_attributes);
        }
}

/* Check Email in sysytem or not */
add_action('wp_ajax_nopriv_check_requested_email_insystem_ajax', 'check_requested_email_insystem_ajax'); 
add_action('wp_ajax_check_requested_email_insystem_ajax', 'check_requested_email_insystem_ajax');
function check_requested_email_insystem_ajax()
{
	if(isset($_POST['email']))
	{
		$email = $_POST['email'];
		$user_data = get_user_by( 'email', $email ); // return WP_User object otherwise return false if not found 
		
		if( !is_wp_error($user_data) ) {
			if ( !empty( $user_data ) ) {	
			echo "yes";
			}
		} else {
			echo $user_data->get_error_message();
		}
		//exit;
	}
}
// Profile Images Crop
if ( ! function_exists( 'profile_image_resize_crop' ) ) {
function profile_image_resize_crop ( $src, $w, $h, $dest = null, $override = true, $createNewIfExists = true ) {
	if ( empty( $src ) || ! is_string( $src ) ) {
		return $src;
	}
	$parts = explode( '.', $src );
	$ext = strtolower( (string) end( $parts ) );
	$filenameSrc = str_replace( ".$ext", '', basename( $src ) );
	$filename = "{$filenameSrc}-{$w}X{$h}";
	$arrayUploadPath = wp_upload_dir();
	$fileUploadSubDir = "/profile/";
	$fileUploadDir = $arrayUploadPath['basedir'] . "/profile/";

	if ( ! file_exists( $fileUploadDir ) ) {
		wp_mkdir_p( $fileUploadDir );
	}

	if ( is_null( $dest ) ) {
		$dest = $fileUploadDir;
	}

	$i = '';
	if ( ! $override && $createNewIfExists ) {
		$count = 0;
		while ( file_exists( "$dest$filename-$count.jpg" ) ) {
			$count++;
		}
		$i = '-' . $count;
	}

	$fileFullPath = "$dest$filename$i.jpg";
	$fileFullUrl = $arrayUploadPath['baseurl'] . $fileUploadSubDir . $filename . $i . '.jpg';

	if ( ! $override && file_exists( $fileFullPath ) ) {
		return $fileFullUrl;
	}

	if ( $override && file_exists( $fileFullPath ) ) {
		@unlink( $fileFullPath );
	}

	$image = false;
	try {
		switch ( $ext ) {
			case 'jpg':
			case 'jpeg':
				$image = @imagecreatefromjpeg( $src );
				break;
			case 'gif':
				$image = @imagecreatefromgif( $src );
				break;
			case 'png':
				$image = @imagecreatefrompng( $src );
				break;
			case 'wbmp':
				$image = @imagecreatefromwbmp( $src );
				break;
			case 'webp':
				$image = function_exists( 'imagecreatefromwebp' ) ? @imagecreatefromwebp( $src ) : false;
				break;
			case 'bmp':
				$image = function_exists( 'imagecreatefrombmp' ) ? @imagecreatefrombmp( $src ) : false;
				break;
			default:
				$image = false;
		}
	} catch ( \Throwable $t ) {
		$image = false;
	}

	if ( ! $image ) {
		return $src;
	}

	$width = imagesx( $image );
	$height = imagesy( $image );
	if ( ! $width || ! $height ) {
		imagedestroy( $image );
		return $src;
	}

	$original_aspect = $width / $height;
	$thumb_aspect = $w / $h;

	if ( $original_aspect >= $thumb_aspect ) {
		if ( $width > $w ) {
			$new_height = $h;
			$new_width = $width / ( $height / $h );
		} else {
			$new_height = $height;
			$new_width = $width;
		}
	} else {
		if ( $width > $w ) {
			$new_width = $w;
			$new_height = $height / ( $width / $w );
		} else {
			$new_width = $width;
			$new_height = $height;
		}
	}

	$thumb = imagecreatetruecolor( $w, $h );
	if ( ! $thumb ) {
		imagedestroy( $image );
		return $src;
	}

	$bg = imagecolorallocate( $thumb, 255, 255, 255 );
	imagefill( $thumb, 0, 0, $bg );

	imagecopyresampled(
		$thumb,
		$image,
		0 - ( $new_width - $w ) / 2,
		0 - ( $new_height - $h ) / 2,
		0, 0,
		$new_width, $new_height,
		$width, $height
	);

	imagejpeg( $thumb, $fileFullPath, 90 );
	imagedestroy( $thumb );
	imagedestroy( $image );

	return $fileFullUrl;
}
}


add_action('wp_ajax_nopriv_confirm_code_fundraiser', 'confirm_code_fundraiser');
add_action('wp_ajax_confirm_code_fundraiser', 'confirm_code_fundraiser');
function confirm_code_fundraiser(){
	$ID = $_POST['request_id'];
	
	$query=$ID;
	if ( preg_match( '/([0-9]{6})/', $query) == 1) 
	 {
		$user_info = get_userdata($ID);
		if(in_array( 'fundraiser', (array) $user_info->roles ))
		{
			$fundr_status = get_user_meta( $ID, 'user_action_status' , true );
			if($fundr_status==2)
			{
				$status_code=1;
			}
			else{
				$status_code=0;
			}
		}
		if(in_array( 'fundraiser', (array) $user_info->roles ) && $status_code==0)
		{
// 			$group_name=get_user_meta($ID, "user_registration_input_company", true);
$group_name=get_user_meta($ID, "user_registration_name_of_school_business", true);
		?>
		<style>
.manual-mass-popup
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: block;
	}
.manual-mass-popup .manual-mass-order-popup-wrapper
{
    display: block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
    padding: 40px 25px;
    background: #fff;
    border: 3px solid #000;
    border-radius: 5px;
}
.manual-mass-popup a.fr-btn 
{
    display: block;
    margin: 20px auto;
    width: 100%;
    max-width: 250px;
}
#manual-mass-popup .show
{
	    background: #fff;
    color: #000;
    margin: 0 auto;
    width: 100%;
    text-align: center;
}
.manual-mass-popup a.fr-btn
{
    display: block;
    margin: 0px auto;
    width: 100%;
    background: none;
    color: #000;
	max-width: 100%;
	display: inline;
    margin: 0px auto;
    width: auto;
	font-size: 25px;
}
#m-link
{
   font-weight: 700;
    background: #000;
    padding: 10px 49px;
    font-size: 15px;
    letter-spacing: 0.6px;
    display: inline-block;
    margin-top: 15px;
    color: #fff;
    border: 1px solid #000;
    border-radius: 30px;
}
a#m-link:hover {
    background: #fff;
    color: #000;
    border: 1px solid #000;
    border-radius: 30px;
}
#m-link-yes
{
    display: inline-block;
    margin-top: 10px;
    margin-right: 10px;
    background: rgb(197 187 187 / 73%);
    padding: 6px 10px;
    font-size: 14px;
    letter-spacing: 0.6px;
    text-align: center;
    text-decoration: none !important;
	display:none;
}
.manual-mass-popup .f_msg
{
	letter-spacing: 0.4px;
	font-size: 16px;
}
.manual-mass-popup small
{
	    display: block;
    font-size: 15px;
    margin-bottom: 8px;
}
.manual-mass-popup h3
{
	    margin-bottom: 5px;
}
.manual-mass-popup .linecode
{
	    margin-bottom: 6px;
    font-size: 19px;
    color: #000;
}
</style>
<div class="manual-mass-popup">
        <div class="manual-mass-order-popup-wrapper">
		
		<h3><i class="fa fa-check-circle-o" aria-hidden="true" style="font-size: 100px;"></i></h3>
		<p class="linecode">Please confirm. Are you sure?</p>
     <span class="f_msg"><b style="font-size: 16px;">Fundrasier ID:</b> <?php echo "#".$ID; ?></span><br/>
	   <span class="f_msg"><b style="font-size: 16px;">Group Name:</b> <?php echo $group_name; ?></span><br/>
	        <a href="#" id="m-link-yes" class="txt-link">Cancel</a>
			 <a href="#" id="m-link" class="txt-link">Okay</a>
			
        </div>
    </div>
		<?php
		}
	/*	$action="fundrasier";
		wp_send_json_success($action); */
		

	 }
}

add_action('wp_ajax_nopriv_confirm_code_fundraiser_new', 'confirm_code_fundraiser_new'); 
add_action('wp_ajax_confirm_code_fundraiser_new', 'confirm_code_fundraiser_new');
function confirm_code_fundraiser_new(){
	$ID = $_POST['request_id'];
	
	$query=$ID;
	if ( preg_match( '/([0-9]{6})/', $query) == 1) 
	 {
		$user_info = get_userdata($ID);
		if(in_array( 'fundraiser', (array) $user_info->roles ))
		{
			$fundr_status = get_user_meta( $ID, 'user_action_status' , true );
			if($fundr_status==2)
			{
				$status_code=1;
			}
			else{
				$status_code=0;
			}
		}
		if(in_array( 'fundraiser', (array) $user_info->roles ) && $status_code==0)
		{
			     //   $group_name=get_user_meta($ID, "user_registration_input_company", true);
			     $group_name=get_user_meta($ID, "user_registration_name_of_school_business", true);
			
					    $f=get_user_meta( $ID , 'first_name' , true );		
						$l=get_user_meta( $ID , 'last_name' , true );	
						$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
						$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
				// 		$org=get_user_meta( $ID , 'user_registration_input_company' , true );
				$org=get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
						if(empty($org))
                        {
                            $org=get_user_meta( $ID, 'user_registration_input_company', true );
                        }
						$goal_amount=get_user_meta( $ID , 'user_registration_fr_goal' , true );
						$fund_desc=get_user_meta($ID,'fund_profile_description',true);
						$fund_image=get_user_meta($ID,'fund_profile_image',true);
						$reason_for_fundraising =  get_user_meta($ID,'reason_for_fundraising',true);
						//$user_registration_fund_destination=get_user_meta($ID,'user_registration_fund_destination',true);
					//	$user_registration_fund_destination=get_user_meta($ID,'user_registration_reason_for_fundraising',true);
						$user_registration_fund_destination=get_user_meta($ID,'fund_profile_description',true);
						if(empty($user_registration_fund_destination))
						{
							$user_registration_fund_destination=get_user_meta($ID,'user_registration_reason_for_fundraising',true);
							if(empty($user_registration_fund_destination))
							{
								$user_registration_fund_destination=get_user_meta($ID,'user_registration_fund_destination',true);
							}
						}
						$originalDate = $sd;
						$originalDate2 = $ed;
						$newDate = date("F d", strtotime($originalDate));
						$newDate2 = date("F d", strtotime($originalDate2));
							$total_sale_so_far=0;
							$rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) 
							{
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

								/*   if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
									/*Active / inactive*/
									 $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
										$extract_date=$order_date;
										$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
										$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
									if ($in_s ==1){
									
									
								  if ($frID3 != $order_customer_id) {
									   $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
                              $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								  }
								} 
								 else if ($frID3 == $order_customer_id) {
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
								 }
								} 
								else{}
								} }
                            /*     } */
						}
							/* Ship to School Total Sales and Rebate */
						/* Calculate Catlog Quantity */
						 $arr_order_id2=array();
						 $total_cat_quantity =0; 						
						 $total_sale_so_far2=0;
						 $rebate_so_far2=0;		
						 $item_qty_fund_online=0;
						 
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($order->has_status('on-hold') || $order->has_status('completed') || $order->has_status('processing')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
								} } }
						}
							   
							   
							   
                            $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								
									$arr_order_id2[]=$order->get_id();
								
                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                        foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						$percentage  = 40;
                        $percentFifty= 50;
					    $real_qty_catlog_online=$total_cat_quantity+$item_qty_fund_online;
						if ($real_qty_catlog_online > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1))
						{
                            $Discount    = ($percentFifty / 100) * $total_sale_so_far2;
							$rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
                        else
						{
                            $Discount    = ($percentage / 100) * $total_sale_so_far2;
                            $rebate_so_far2 = number_format($Discount, 2, '.', '');
							
						}
						
						$rebate_so_far3=$rebate_so_far+$rebate_so_far2; 
						$rebate_so_far3=number_format($rebate_so_far3, 2, '.', '');
						 // End Ship to school	

				   ?>
	<script>
			 jQuery(document).on('click', '#close_id, #ok_id', function (e) 
			{
				e.preventDefault();
				jQuery("#brave_popup_12975").css("display","none");
			});
	</script>
		<style>
	#brave_popup_12975 {
    z-index: 1000000000;
}
.brave_popup {
    width: 100%;
    float: left;
    position: absolute;
    z-index: 999999999;
    display: block;
    direction: ltr;
}
.brave_popup .brave_popup__step.has_overlay {
    position: fixed;
    width: 100%;
    height: 100vh;
    z-index: 9999999;
    left: 0;
    top: 0;
}
.brave_popup .brave_popup__step__inner {
    transform: translateX(0);
    position: fixed;
    z-index: 9;
    display: block;
}
.brave_popup .brave_popup__step.position_center .brave_popup__step__inner {
    top: 50%;
    left: 0;
    right: 0;
    bottom: auto;
    margin: 0;
    margin-left: auto!important;
    margin-right: auto!important;
    outline: none;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__inner {
    width: 940px;
    height: 440px;
    border-radius: 24px;
    margin-top: -220px;
    font-family: brave_custom-avenirnext;
}
.brave_popup .brave_popup__step__inner .brave_popupSections__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
}
.brave_popup .brave_popup__step__inner .brave_popupMargin__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
    outline: none;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popupMargin__wrap {
    top: 0px;
    left: 0px;
}
.brave_popup .brave_popup__close {
    position: absolute;
    cursor: pointer;
    padding: 3px;
    z-index: 99999;
    transition: all .14s linear;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    width: auto!important;
}
.brave_popup .brave_popup__close.brave_popup__close--icon {
    opacity: .5;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    top: 5px!important;
    right: 7px;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__close {
    font-size: 30px;
    width: 30px;
    color: rgba(0,0,0, 1);
    top: -40px;
}
svg {
    overflow: hidden;
    vertical-align: middle;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__close svg {
    width: 30px;
    height: 30px;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup {
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__popup {
    border-radius: 24px;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content {
    width: 100%;
    height: 100%;
    float: left;
    letter-spacing: 0;
    overflow: hidden;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__content {
    background-color: rgba(255,255,255, 1);
}
.brave_popup .brave_element {
    position: absolute;
    will-change: transform;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content a, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content div, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content li, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content ol, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content p, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content span, .brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content ul {
    font-family: inherit;
    line-height: inherit;
    font-weight: inherit;
    color: inherit;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content .brave_popup__step__elements {
    position: relative;
    z-index: 1;
    width: 1024px;
    height: 100%;
    margin: 0 auto;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf {
width: 300px;
    height: 333.56848549888px;
    top: 52.96875px;
    left: 43.871536254883px;
    z-index: 0;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_element__wrap {
    font-family: brave_custom-avenirnext;
}
.brave_popup .brave_element .brave_element__wrap {
    position: relative;
    min-height: 20px;
    height: 100%;
    width: 100%;
    scrollbar-color: #ddd transparent;
    scrollbar-width: thin;
}
.brave_popup .brave_element.brave_element--image .brave_element__styler {
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: relative;
    box-sizing: border-box;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 490px;
    height: auto;
    top: 50px;
    left: 380px;
    z-index: 1;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
    text-align: left;
    font-size: 20px;
    font-family: 'brave_custom-Sans pro';
    line-height: 1.7em;
    color: rgba(138,138,138, 1);
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf .brave_element__styler {
    border-radius: 30px;
}
.brave_popup .brave_element.brave_element--image .brave_element__inner {
    width: 100%;
    height: 100%;
}
.brave_popup .brave_element.brave_element--image .brave_element__styler img {
    width: 100%;
    height: 100%;
    position: absolute;
    -o-object-fit: cover;
    object-fit: cover;
    vertical-align: bottom;
    -o-object-position: top;
    object-position: top;
    max-width: unset;
}
.brave_popup .brave_element.brave_element--text .brave_element__styler {
    font-size: 13px;
    line-height: 1.7em;
    padding: 5px;
    color: #000;
    font-weight: 400;
    font-style: normal;
    text-decoration: none;
    text-align: left;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    text-align: left;
    font-size: 42px;
    font-family: 'Roboto';
    line-height: 1.3;
    font-weight: bold;
    color: rgba(0,0,0, 1);
}
.brave_popup .brave_popup__step__inner {
    transform: translateX(0);
    position: fixed;
    z-index: 9;
    display: block;
}
.brave_popup .brave_popup__step.position_center .brave_popup__step__inner {
    top: 50%;
    left: 0;
    right: 0;
    bottom: auto;
    margin: 0;
    margin-left: auto!important;
    margin-right: auto!important;
    outline: none;
}
.brave_popup .brave_popup__step__inner .brave_popupSections__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
}
.brave_popup .brave_popup__step__inner .brave_popupMargin__wrap {
    width: 100%;
    height: 100%;
    will-change: transform;
    position: relative;
    outline: none;
}
.brave_popup .brave_popup__close {
    position: absolute;
    cursor: pointer;
    padding: 3px;
    z-index: 99999;
    transition: all .14s linear;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    width: auto!important;
}
.brave_popup .brave_popup__close.brave_popup__close--icon {
    opacity: .5;
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    top: 5px!important;
    right: 7px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 450px;
    height: auto;
    top: 172px;
    left: 380px;
    z-index: 2;
	    top: 157px;

}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
    text-align: left;
    font-size: 15px;
    font-family: 'ROBOTO';
    line-height: 1.4;
    color: rgb(0 0 0);
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
    width: 274px;
    height: 39px;
    top: 350px;
    left: 386px;
    z-index: 3;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 .brave_element__styler {
    font-size: 20px;
    font-family: brave_custom-Sans pro semi bold;
    font-weight: bold;
    color: rgba(255,255,255, 1);
    background-color: #000;
    line-height: 39px;
}
.brave_popup .brave_element.brave_element--button .brave_element__styler .brave_element__inner_link {
    display: block;
    color: inherit;
    width: 100%;
    text-align: center;
}
.brave_popup .brave_popup__step__overlay {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0,0,0,.5);
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__overlay {
    background-color: rgba(60,60,60, 0.7);
}
.brave_popup .brave_popup__close.brave_popup__close--inside_right {
    top: 3px!important;
    right: 16px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 490px;
    height: auto;
    top: 84px;
    left: 380px;
    z-index: 1;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 450px;
    height: auto;
    top: 217px;
    left: 380px;
    z-index: 2;
	top: 172px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
    width: 85px;
    height: 39px;
    top: 360px;
    left: 386px;
    z-index: 3;
}
@media screen and (min-width: 760px){
.brave_popup__step_wrap--show .brave_popup__step__desktop {
    display: block;
}
}
 #brave_element--ok
 {
    width: 475px;
    height: auto;
    top: 211px;
    left: 380px;
 }
textarea {
    width: 100%;
    height: 110px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
}
div#brave_element-IDDD {
    width: 490px;
    height: auto;
    top: 63px;
    left: 382px;
    z-index: 1;
}
#brave_element-IDDD .brave_element__text_inner {
    text-align: left;
    font-size: 16px;
    font-family: 'Roboto' !important;
    line-height: 1.3 !important;
    font-weight: bold !important;
    color: rgba(0,0,0, 1) !important;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    text-align: left;
    font-size: 25px;
    font-family: 'Roboto';
    line-height: 1.3;
    font-weight: bold;
    color: rgba(0,0,0, 1);
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 490px;
    height: auto;
    top: 92px;
    left: 380px;
    z-index: 1;
}
div#brave_element--WWW {
     width: 490px;
    height: auto;
    top: 184px;
    left: 384px;
    z-index: 1;
}
div#brave_element--WWW .sc-hwwEjo.eoCznv {
    text-align: left;
    font-size: 36px;
    font-family: 'Roboto';
    line-height: 1.3;
    font-weight: bold;
    color: rgba(0,0,0, 1);
}
#brave_element--WWW #fundraising_dates
{
	    font-family: 'Roboto', Georgia, sans-serif;
    font-size: 17px;
}
#brave_element--WWW .eYHKCV, #brave_element--WWW .jZDald {
    font-size: 16px;
    font-family: 'Roboto';
}
#brave_element--WWW .progress.progressBar
{
	margin-top: 7px;
}
div#brave_element--WWW {
    width: 490px;
    height: auto;
    top: 236px;
	/* top: 184px; */
    left: 384px;
    z-index: 1;
	padding-top: 12px;
}
#brave_element--N3sYSN80c2sV-aMSMJ9 .brave_element__button_text
{
	    font-family: 'Roboto';
}
#brave_element--WWW .kZLEoE {
    color: rgb(50, 50, 50);
    font-size: 17px;
    font-style: normal;
    line-height: 27px;
    margin: 0px;
    font-weight: 400;
}
.eGOSHP
{
    padding: 11px 30px;
    display: inline-block;
}
.brJQDe {
     height: auto;
}
#brave_element--WWW .brave_element__styler 
{
	/* padding-left: 0; */
}
div#brave_element--WWW .sc-dqBHgY.ccbzhW
 {
    width: 100%;
    display: inline-flex;
}
div#brave_element--WWW .sc-hwwEjo.eoCznv
{
	display: flex;

}
@media only screen and (max-width: 990px) 
{
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf
{
	    display: none;
}
div#brave_element-IDDD {
    width: 95%;
	left: 30px;
}
div#brave_element--WWW
{
	    width: 50%;   
		left: 30px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 90%;
    left: 30px;

}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh
{
	 width: 95%;
 left: 30px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9
{
	    left: 30px;
}
.brave_popup .brave_popup__step__inner .brave_popup__step__popup .brave_popup__step__content .brave_popup__step__elements
{
	width: auto;
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__inner {
    width: 95%;
}

}
#brave_element--WWW .progress.progressBar {
    margin-top: 7px;
}
.progressBar
{
	    margin: 25px 0px 15px;
    width: 100%;
	 height: 12px;
	     background: rgb(239, 238, 238);
}

.progress-bar {
   height: 12px;
       background: rgb(23, 198, 113);
}
.ccbzhW {
    display: flex;
    flex-flow: row wrap;
    -webkit-box-pack: justify;
    justify-content: space-between;
}
div#brave_element--WWW .sc-hwwEjo.eoCznv {
    text-align: left;
    font-size: 36px;
    font-family: 'Roboto';
    line-height: 1.3;
    font-weight: bold;
    color: rgba(0,0,0, 1);
	display: inherit;
}
.eYHKCV {
    padding-left: 6px;
}
#brave_element--WWW .eYHKCV, #brave_element--WWW .jZDald {
    font-size: 16px;
    font-family: 'Roboto';
}
@media only screen and (max-width: 664px)
{
	#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {

    font-size: 27px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    font-size: 22px;
}
div#brave_element-IDDD {
    width: 95%;
    left: 30px;
}
div#brave_element--WWW {
    width: 90%;
    left: 30px;
	top: 170px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng {
    width: 89%;
    left: 30px;
	    top: 95px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 93%;
    left: 30px;
	top: 168px;   
}
#brave_element--WWW .eYHKCV, #brave_element--WWW .jZDald {
    font-size: 13px;
    font-family: 'Roboto';
}
.eYHKCV {
    padding-left: 0;
    /* display: block; */
}
#brave_popup_12975__step__0 .brave_popup__step__desktop .brave_popup__step__inner
{
	height: 420px;
}
div#brave_element--WWW
{
	    top: 226px;
}
div#brave_element--WWW {
    top: 163px;
	  top: 226px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
    top: 337px;
}
#brave_element--WWW .kZLEoE {
    color: rgb(50, 50, 50);
    font-size: 15px;
}
.home #brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnf {
    width: 170px !important;
    height: 170px !important;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
   
    font-size: 14px;
}
}
@media only screen and (max-width: 420px)
{
	#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
        top: 300px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    font-size: 22px;
}
.eYHKCV {
    padding-left: 0;
    /* display: block; */
}
}
@media only screen and (max-width: 380px){
div#brave_element--WWW {
    width: 77%;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh {
    width: 79%;
    left: 30px;
   
}
}
@media only screen and (max-width: 320px){
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    font-size: 20px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
   
    font-size: 14px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mnh .brave_element__text_inner {
    font-size: 13px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
    top: 358px;
}
}
	</style>
	<div class="brave_popup brave_popup--popup" id="brave_popup_12975" data-loaded="true" data-triggerfulfilled="true">
   <!-- <p></p> -->
   <div id="brave_popup_12975__step__0" class="brave_popup__step_wrap brave_popup__step--mobile-noContent brave_popup__step_wrap--show">
      <div class="brave_popup__step brave_popup__step__desktop  position_center closeButton_icon brave_popup__step--boxed closeButtonPosition_inside_right has_overlay has_overlay_close" data-nomobilecontent="true" data-scrollock="" data-width="940" data-height="440" data-open="true" style="z-index:99999" data-exitanimlength="0.5" data-layout="boxed" data-position="center">
         <div class="brave_popup__step__inner">
            <div class="brave_popupSections__wrap" style="">
               <div class="brave_popupMargin__wrap" tabindex="0">
                  <div class="brave_popup__step__close">
                     <div id="close_id" class="brave_popup__close brave_popup__close--inside_right brave_popup__close--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                           <rect x="0" fill="none" width="20" height="20"></rect>
                           <g>
                              <path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"></path>
                           </g>
                        </svg>
                     </div>
                  </div>
                  <div class="brave_popup__step__popup">
                     <div class="brave_popup__step__content">
                        <div class="brave_popup__step__elements">
                           <div class="brave_popup__elements_wrap ">
                              <div id="brave_element--N3sYSN7DAn8xy7o0Mnf" class="brave_element brave_element--image   ">
                                 <div class="brave_element__wrap">
                                    <div class="brave_element__styler">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__image_inner 123">
										  <!--
                                             <img width="300" height="300" class="brave_element__image  brave_element_img_item skip-lazy no-lazyload" data-lazy="" src="https://devglow.wpengine.com/wp-content/uploads/2022/10/3d-cartoon-character-young-woman-call-center-customer-service-using-laptop_40876-3098.jpg" alt="">
											-->
										 <?php
												$image_url_profile=get_user_meta($ID,"user_registration_image_url_profile",true);
												if(!empty($fund_image)) { 
												$crop_url_profile1=profile_image_resize_crop( $src=$fund_image, $w=512, $h=512, $dest = null, $override = true, $createNewIfExists = true );
												?>
												 <img width="300" height="300" class="brave_element__image  brave_element_img_item skip-lazy no-lazyload" data-lazy="" src="<?php echo $crop_url_profile1; ?>" alt="">
												<?php }
												else if(!empty($image_url_profile)) {
													$crop_url_profile2=profile_image_resize_crop( $src=$image_url_profile, $w=512, $h=512, $dest = null, $override = true, $createNewIfExists = true );		
												?>
												 <img width="300" height="300" class=" brave_element__image  brave_element_img_item skip-lazy no-lazyload" data-lazy="" src="<?php echo $crop_url_profile2; ?>" alt="">
												<?php	
												}												
												else {	
												?>
			 <img width="300" height="300" class="brave_element__image  brave_element_img_item skip-lazy no-lazyload" data-lazy="" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/10/3d-cartoon-character-young-woman-call-center-customer-service-using-laptop_40876-3098.jpg" alt="">									
												<?php } ?>
										  </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
							   <div id="brave_element-IDDD" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner">Fund ID: <?php echo "#".$ID; ?></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
							  <div id="brave_element--WWW" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
									   <h3 class="sc-dfVpRl kZLEoE">Support Now</h3>
									    <div class="progress progressBar">
										<?php $width=round($rebate_so_far3 / ($goal_amount / 100),2); ?>
											<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width."%"; ?>;"></div>
										</div>
                                         <div class="sc-dqBHgY ccbzhW">
											<div class="sc-hwwEjo eoCznv">
											   <div width="55" class="sc-kTUwUJ jZDald">$<?php echo $rebate_so_far3=number_format($rebate_so_far3, 2, '.', '')." "; ?></div>
											   <div class="sc-kTUwUJ eYHKCV"> / $<?php echo $goal_amount; ?></div>
											</div>
											<div id="#fundraising_dates" class="sc-kTUwUJ eYHKCV"><?php  echo $newDate; ?> - <?php  echo $newDate2; ?></div>
										 </div>
										 
                                       </div>
                                    </div>
                                 </div>
                              </div>	

                              <div>
                              	<p>Reason for Fundraising (A message to be read by your customers for why you are fundraising/how the funds raised will be used.)</p>
                              	<p><?php echo $reason_for_fundraising; ?></p>
                              </div>
							
                              <div id="brave_element--N3sYSN7DAn8xy7o0Mng" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner"><?php echo $org; ?></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div id="brave_element--N3sYSN7DAn8xy7o0Mnh" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner"><?php if(!empty($user_registration_fund_destination)){  echo $user_registration_fund_destination=wp_trim_words($user_registration_fund_destination,30,' [...]');  } else{ "Help us reach our goal! Thank you for the support!"; } ?></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
							  <!--<div id="brave_element--ok" class="brave_element brave_element--text ">
                                 <div class="brave_element__wrap ">
                                    <div class="brave_element__styler ">
                                       <div class="brave_element__inner">
                                          <div class="brave_element__text_inner">
										  <form>
											  <textarea>Enter your text...</textarea>
											</form>
									     </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>-->
                              <div id="brave_element--N3sYSN80c2sV-aMSMJ9" class="brave_element brave_element--button  brave_element--has-click-action exit-intent-popup-button">
                                 <div class="brave_element__wrap">
                                    <div class="brave_element__inner">
                                       <div class="brave_element__styler">
                                          <a id="ok_id" class="brave_element__inner_link">
                                             <div class="brave_element__button_text">OK</div>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="brave_popup__step__overlay brave_popup__step__overlay--closable" onclick="brave_close_popup('12975', 0)"></div>
      </div>
     
   </div>
</div>
		<?php
		}
	/*	$action="fundrasier";
		wp_send_json_success($action); */
		

	 }
}





/*
** Ajax for Validate fundraiser Code for Seller Register
*/
add_action('wp_ajax_nopriv_ajax_seller_register_FID_validation', 'ajax_seller_register_FID_validation');
add_action('wp_ajax_ajax_seller_register_FID_validation', 'ajax_seller_register_FID_validation');
function ajax_seller_register_FID_validation( )
{
	$ID = $_POST['fund_id'];
	$query=$ID;
	if ( preg_match( '/([0-9]{6})/', $query) == 1) 
	{
		$user_info = get_userdata($ID);
		if(in_array( 'fundraiser', (array) $user_info->roles ))
		{
			$fundr_status = get_user_meta( $ID, 'user_action_status' , true );
			if($fundr_status==2)
			{
				$status_code=4;
				wp_send_json_success(array(
				'action' => $status_code));
			}
			else
			{
				$res=1;
				wp_send_json_success(array(
				'action' => $res));
			}
			
		}
		else
		{
			$res=2;
			wp_send_json_success(array(
			'action' => $res));
		}
	}
	else
	{
		$res=0;
		wp_send_json_success(array(
			'action' => $res));
	}
}

/*
*  Drop Down List on ajax call
*/
add_action('wp_ajax_nopriv_seller_drop_down_list', 'seller_drop_down_list'); // for not logged in users
add_action('wp_ajax_seller_drop_down_list', 'seller_drop_down_list');
function seller_drop_down_list()
{
	$ID = $_POST['request_id'];
	$query=$ID;
	if ( preg_match( '/([0-9]{6})/', $query) == 1) 
	 {
		$user_info = get_userdata($ID);
		if(!in_array( 'fundraiser', (array) $user_info->roles ))
		{
		?>
		<option value="No-seller" selected="selected">No Seller</option> 		
		<?php
		}
		if(in_array( 'fundraiser', (array) $user_info->roles ))
		{
			$fundr_status = get_user_meta( $ID, 'user_action_status' , true );
			if($fundr_status==2 && !is_user_logged_in())
			{
				$status_code=1; //inactive
			?>
			<option value="No-seller" selected="selected">No Seller</option> 
			<?php
			}
			else if((empty($fundr_status) || $fundr_status==1) || ($fundr_status==2 && is_user_logged_in() && $_SESSION['sellername']=='Catlog' ))
			{
				$status_code=2; //active
				
			}
			else
			{
				
			}
		}	
		if(in_array( 'fundraiser', (array) $user_info->roles ) && $status_code==2)
		{	
			 $args2 = array(
                'role'    => 'seller',
                'orderby' => 'user_nicename',
                'order'   => 'ASC'
            );
            $sellerUsers = get_users( $args2 );
			$all_seller_found = get_users(array(
										'meta_key'     => 'user_registration_seller_fundraiser',
										'meta_value'   => $ID,
									));
			if(isset($_POST['glow_seller']))
			{
				$str=$_POST["glow_seller"];
		     	$str_n=explode(" ",$str);
				$open=0;
				
				foreach ( $all_seller_found as $sellerUser_found ) { 						
									$s_f_t=$sellerUser_found->first_name; 
									$s_l_t= $sellerUser_found->last_name;
									 if(strtolower($str_n[0]) == strtolower($s_f_t) && strtolower($str_n[1])== strtolower($s_l_t)) {
										 $check_result=1;
									 }
			
				 }
			}
			if(!isset($_POST['glow_seller']))
			{
		?>
		
		<option value="No-seller" selected="selected">No Seller</option> 
		<?php
			}
            foreach ( $all_seller_found as $sellerUser ) { ?>


                <option frdata="<?php echo get_user_meta( $sellerUser->ID, 'user_registration_seller_fundraiser', true ); ?>" value="<?php echo $sellerUser->ID; ?>" 

                	<?php


                	global $wpdb;

$user_id = $sellerUser->ID; // replace with your user ID

$query = "
    SELECT 
        MAX(CASE WHEN meta_key = 'first_name' THEN meta_value END) AS first_name,
        MAX(CASE WHEN meta_key = 'last_name' THEN meta_value END)  AS last_name
    FROM {$wpdb->usermeta}
    WHERE user_id = %d
";

$user_name = $wpdb->get_row( $wpdb->prepare($query, $user_id) );

if ( $user_name ) {
    $first_name= esc_html($user_name->first_name);
    $last_name= esc_html($user_name->last_name);
} 


$fullname =$first_name . ' ' . $last_name;

                	 $s_f=$sellerUser->first_name; $s_l= $sellerUser->last_name; 

                	if($fullname == $_POST['glow_seller']) { $open=1;?> id="selectSeller" <?php } ?>>
				
				<?php 

				





echo $fullname;





				 ?> 
			
			
			</option>
        <?php
            } ?><option  frdata="custom" value="custom" class="add-seller" <?php if($open !=1 && !empty($str)){ ?> id="selectCustom" <?php } ?> >Add Seller</option>
	
		<?php
		}
		
	}
}

/*
*  Feedback ajax call
*/
add_action('wp_ajax_nopriv_feedback_ajax', 'feedback_ajax'); // for not logged in users
add_action('wp_ajax_feedback_ajax', 'feedback_ajax');
function feedback_ajax()
{
	$u_id=$_POST['fid'];
	$feedbackStatus = get_user_meta($u_id,'feedback_status',true);
	if(empty($feedbackStatus) && $feedbackStatus!="Yes")
	{
			if(isset($_POST['fid']))
       	   {
			$kindOfFeedback=$_POST['kindOfFeedback'];
			$share=$_POST['share'];
			$cemail=$_POST['cemail'];
			$smile=$_POST['smile'];
			$feel=$_POST['friends'];
			
				if($feel==1)
					{
						$n="&#128522;";
					}
					else if($feel==2)
					{
						$n="&#128525;";
					}
					else if($feel==3)
					{
						$n="&#128528;";
					}
					else if($feel==4)
					{
						$n="&#128549;";
					}
					else
					{
						
					}
					
			$post_id = wp_insert_post(array('post_title'=>'feedback-'.$u_id, 'post_type'=>'feedback'));	
			if(!empty($post_id))
			{
				update_post_meta($post_id,'feedback_user_id',$u_id);
				update_post_meta($post_id,'feedback_feel',$n);
				update_post_meta($post_id,'feedback_type',$kindOfFeedback);
				update_post_meta($post_id,'feedback_share',$share);
				update_post_meta($post_id,'feedback_email',$cemail);
				update_post_meta($post_id,'feedback_friends',$smile);
				update_user_meta($u_id,'feedback_status',"Yes");
				$res="feedback submitted successfully!!";
				wp_send_json_success($res);
			} 
		}
    }
	else
	{
		if(!empty($feedbackStatus) && $feedbackStatus=="Yes")
	    {
		    $res="Feeback is already submiited";	
			wp_send_json_success($res);
		}
		else
		{
			 $res="Something went wrong!!";	
			 wp_send_json_success($res);
		}
	}
}			

/* Backend */	
// Hooks near the bottom of profile page (if current user) 
add_action('show_user_profile', 'custom_user_profile_fields');

// Hooks near the bottom of the profile page (if not current user) 
add_action('edit_user_profile', 'custom_user_profile_fields');

// @param WP_User $user
function custom_user_profile_fields( $user ) {
?>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'Feedback Status' ); ?></label>
            </th>
            <td>
                <input type="text" name="feedback_status" id="feedback_status" value="<?php echo esc_attr( get_the_author_meta( 'feedback_status', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
		 <tr>
            <th>
                <label for="mergeOrderID"><?php _e( 'Merge Order ID' ); ?></label>
            </th>
            <td>
                <input type="text" name="mergeOrderID" id="mergeOrderID" value="<?php echo get_user_meta( $user->ID, 'wc_ship_mergeOrderID', true ); ?>" class="regular-text" />
            </td>
        </tr>
		 <tr>
            <th>
                <label for="first_catlog_order"><?php _e( 'First Catlog Order' ); ?></label>
            </th>
            <td>
                <input type="text" name="first_catlog_order" id="first_catlog_order" value="<?php echo $first_catalog_order=get_user_meta( $user->ID, 'wc_first_catlog_order', true); ?>" class="regular-text" />
            </td>
        </tr>
		 <tr>
            <th>
                <label for="mergeOrderQuanty"><?php _e( 'Merge Order Total Items' ); ?></label>
            </th>
            <td>
                <input type="text" name="mergeOrderQuanty" id="mergeOrderQuanty" value="<?php echo get_user_meta( $user->ID, 'wc_ship_mergeOrderQuanty', true ); ?>" class="regular-text" />
            </td>
        </tr>
	 <tr>
            <th>
                <label for="glow_profit_deduct_user_metakey_amount"><?php _e( 'Glow Catalog/Brochure Order Total' ); ?></label>
            </th>
            <td>
                <input type="text" name="glow_profit_deduct_user_metakey_amount" id="glow_profit_deduct_user_metakey_amount" value="<?php echo $glow_profit_deduct_user_metakey_amount=get_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_amount', true); ?>" class="regular-text" />
            </td>
        </tr>
		 <tr>
            <th>
                <label for="glow_profit_deduct_user_metakey_ordertype"><?php _e( 'Online Profits Deduction Order Type' ); ?></label>
            </th>
            <td>
                <input type="text" name="glow_profit_deduct_user_metakey_ordertype" id="glow_profit_deduct_user_metakey_ordertype" value="<?php echo $glow_profit_deduct_user_metakey_ordertype=get_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_ordertype', true); ?>" class="regular-text" />
            </td>
        </tr>
		 <tr>
            <th>
                <label for="glow_profit_deduct_user_metakey_orderID"><?php _e( 'Glow Catalog/Brochure Order ID' ); ?></label>
            </th>
            <td>
                <input type="text" name="glow_profit_deduct_user_metakey_orderID" id="glow_profit_deduct_user_metakey_orderID" value="<?php echo $glow_profit_deduct_user_metakey_orderID=get_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_orderID', true); ?>" class="regular-text" />
            </td>
        </tr>
		 <tr>
            <th>
                <label for="glow_profit_deduct_user_metakey_yes"><?php _e( 'Online Profits Deduction' ); ?></label>
            </th>
            <td>
                <input type="text" name="glow_profit_deduct_user_metakey_yes" id="glow_profit_deduct_user_metakey_yes" value="<?php echo $glow_profit_deduct_user_metakey_yes=get_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_yes', true); ?>" class="regular-text" />
            </td>
        </tr>
		<?php
			$user_info = get_userdata($user->ID);
				
			if(in_array( 'fundraiser', (array) $user_info->roles ))
			{
		?>
		 <tr>
            <th>
                <label for="referral_payment_postid"><?php _e( 'Referral Payment PostID' ); ?></label>
            </th>
            <td>
                <input type="text" name="referral_payment_postid" id="referral_payment_postid" value="<?php echo $referral_payment_postid=get_user_meta($user->ID,'referral_payment_postid',true );	?>" class="regular-text" />
            </td>
        </tr>
		<?php
			}
			$user_info = get_userdata($user->ID);
				
			if(in_array( 'representative', (array) $user_info->roles ))
			{
		?>
		 <tr>
            <th>
                <h2><?php _e( 'Representative Payment History' ); ?>:</h2>
            </th>
            <td>
               
            </td>
        </tr>
		<tr>
            <th>
                <label for="um_unique_payment_id"><?php _e( 'Payment Post ID' ); ?></label>
            </th>
            <td>
                <input type="text" name="um_unique_payment_id" id="um_unique_payment_id" value="<?php echo $current_payment_postid=get_user_meta( $user->ID, "um_unique_payment_id", true ); ?>" class="regular-text" />
            </td>
        </tr>
		<tr>
            <th>
                <label for="um_unique_payment_id_arr"><?php _e( 'All Payment IDs' ); ?></label>
            </th>
            <td>
                <input type="text" name="um_unique_payment_id_arr" id="um_unique_payment_id_arr" value="<?php echo $um_unique_payment_id_arr=get_user_meta($user->ID,'um_unique_payment_id_arr',true );	?>" class="regular-text" />
            </td>
        </tr>
		<tr>
            <th>
                <label for="reps_commission"><?php _e( 'Reps Commission' ); ?></label>
            </th>
            <td>
                <input type="text" name="reps_commission" id="reps_commission" value="<?php echo $reps_commission=get_user_meta($user->ID,'reps_commission',true );	?>" class="regular-text" />
            </td>
        </tr>
			<?php } ?>
			<tr>
            <th>
                <label for="wp_meta_first_catlog_order_for_cron_job"><?php _e( 'Crob Job: First Catlog Order' ); ?></label>
            </th>
            <td>
                <input type="text" name="wp_meta_first_catlog_order_for_cron_job" id="wp_meta_first_catlog_order_for_cron_job" value="<?php echo $wp_meta_first_catlog_order_for_cron_job=get_user_meta($user->ID,'wp_meta_first_catlog_order_for_cron_job',true );	?>" class="regular-text" />
            </td>
        </tr>
		<tr>
            <th>
                <label for="wp_meta_seller_barcode_link"><?php _e( 'Seller barcode link' ); ?></label>
            </th>
            <td>
                <input type="text" name="wp_meta_seller_barcode_link" id="wp_meta_seller_barcode_link" value="<?php echo $wp_meta_seller_barcode_link=get_user_meta($user->ID,'seller_barcode_link',true );	?>" class="regular-text" />
            </td>
        </tr>
		<tr>
            <th>
                <label for="wp_meta_fundraiser_barcode_link"><?php _e( 'Fundraiser barcode link' ); ?></label>
            </th>
            <td>
                <input type="text" name="wp_meta_fundraiser_barcode_link" id="wp_meta_fundraiser_barcode_link" value="<?php echo $wp_meta_fundraiser_barcode_link=get_user_meta($user->ID,'fundraiser_barcode_link',true );	?>" class="regular-text" />
            </td>
        </tr>
		<tr>
            <th>
                <label for="wp_meta_fundraiser_sr_barcode_link"><?php _e( 'Fundraiser seller register barcode link' ); ?></label>
            </th>
            <td>
                <input type="text" name="wp_meta_sr_fundraiser_barcode_link" id="wp_meta_sr_fundraiser_barcode_link" value="<?php echo $wp_meta_sr_fundraiser_barcode_link=get_user_meta($user->ID,'fundraiser_seller_register_barcode_link',true );	?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php
}


// Hook is used to save custom fields that have been added to the WordPress profile page (if current user) 
add_action( 'personal_options_update', 'update_extra_profile_fields' );

// Hook is used to save custom fields that have been added to the WordPress profile page (if not current user) 
add_action( 'edit_user_profile_update', 'update_extra_profile_fields' );

function update_extra_profile_fields( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) )
        update_user_meta( $user_id, 'feedback_status', $_POST['feedback_status'] );
	    update_user_meta( $user_id, 'wc_ship_mergeOrderID', $_POST['mergeOrderID'] );
		update_user_meta( $user_id, 'wc_ship_mergeOrderQuanty', $_POST['mergeOrderQuanty'] );
		update_user_meta( $user_id, 'wc_first_catlog_order', $_POST['first_catlog_order'] );
		
		update_user_meta( $user_id, 'glow_profit_deduct_user_metakey_amount', $_POST['glow_profit_deduct_user_metakey_amount']);
		update_user_meta( $user_id, 'glow_profit_deduct_user_metakey_ordertype', $_POST['glow_profit_deduct_user_metakey_ordertype']);
		update_user_meta( $user_id, 'glow_profit_deduct_user_metakey_orderID', $_POST['glow_profit_deduct_user_metakey_orderID']);
		update_user_meta( $user_id, 'glow_profit_deduct_user_metakey_yes', $_POST['glow_profit_deduct_user_metakey_yes']);
		
		update_user_meta( $user_id, 'referral_payment_postid', $_POST['referral_payment_postid']);
		update_user_meta( $user_id, 'um_unique_payment_id', $_POST['um_unique_payment_id']);
		update_user_meta( $user_id, 'um_unique_payment_id_arr', $_POST['um_unique_payment_id_arr']);
		update_user_meta( $user_id, 'reps_commission', $_POST['reps_commission']);
		update_user_meta( $user_id, 'wp_meta_first_catlog_order_for_cron_job', $_POST['wp_meta_first_catlog_order_for_cron_job']);
		
		update_user_meta( $user_id, 'seller_barcode_link', $_POST['wp_meta_seller_barcode_link']);
		update_user_meta( $user_id, 'fundraiser_barcode_link', $_POST['wp_meta_fundraiser_barcode_link']);
		update_user_meta( $user_id, 'fundraiser_seller_register_barcode_link', $_POST['wp_meta_sr_fundraiser_barcode_link']);
		
}
/**
 * Disable messages about the mobile apps in WooCommerce emails.
 * https://wordpress.org/support/topic/remove-process-your-orders-on-the-go-get-the-app/
 */
function mtp_disable_mobile_messaging( $mailer ) {
    remove_action( 'woocommerce_email_footer', array( $mailer->emails['WC_Email_New_Order'], 'mobile_messaging' ), 9 );
}
add_action( 'woocommerce_email', 'mtp_disable_mobile_messaging' );

add_filter( 'woocommerce_email_recipient_failed_order', 'wc_failed_order_email_to_customer', 10, 2 );
function wc_failed_order_email_to_customer( $recipient, $order ){
     if( ! is_a( $order, 'WC_Order' ) ) 
         return $recipient;

     if( $billing_email = $order->get_billing_email() ) 
         $recipient = $billing_email;
     return $recipient;
}
/*
* Order ajax call
*/
add_action('wp_ajax_nopriv_order_ajax', 'order_ajax'); // for not logged in users
add_action('wp_ajax_order_ajax', 'order_ajax');
function order_ajax()
{
	$o_id=$_POST['orderid'];
	$order_type = get_post_meta($o_id,'wc_order_type',true);
	if($order_type=='Online')
	{
		$res="Online";
		wp_send_json_success($res);
	}
	else if($order_type=='Catlog')
	{
		$res="Catlog";
		wp_send_json_success($res);
	}
	else
	{
		$res="none";
		wp_send_json_success($res);
	}
	
}
/*
add_role( 'representative', 'Representative', get_role( 'editor' )->capabilities );
include_once( 'representative.php' );	
*/
/*function representative_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for subscribers
        if (in_array('representative', $user->roles)) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to =  "https://glowbrandscandles.com/reprenstative-dashboard/";
        }
    }

    return $redirect_to;
}

add_filter( 'login_redirect', 'representative_login_redirect', 10, 3 );*/
/*
add_action( 'wp_ajax_nopriv_load-filter', 'prefix_load_cat_posts' );
add_action( 'wp_ajax_load-filter', 'prefix_load_cat_posts' );
function prefix_load_cat_posts () {
	$cat_name = $_POST[ 'cat' ];
    $args = array(
    	'posts_per_page' => 10,
        'product_cat'    => $cat_name
	);
	$products = wc_get_products( $args );    
    ob_start ();

     foreach( $products as $product ) { 
		$productID = $product->get_id(); ?>

   <div class="card card-body">
		<div class="d-flex media align-items-center text-lg-left flex-column flex-lg-row">
			<div class="mr-2 mb-3 mb-lg-0 col-2">
				<?php  $product_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $productID ), 'single-post-thumbnail' ); ?>
				<img src="<?php echo $product_image_url[0];?>" width="90" height="90" alt="">
			</div>
			<div class="media-body col-6">
				<h4 class="media-title font-weight-semibold">
					<a href="#" data-abc="true"><?php echo $product->get_name();?></a>
				</h4>
				<h6 class="mb-0 font-weight-semibold"><?php echo 
				$product->get_regular_price();?></h6>
			</div>
		
			<div class=" text-center col-2">
				<a href=""><?php echo do_shortcode( '[add_to_cart  show_price="true" id="' . $productID . '" quantity="2" style="border:0"]' ) ?></a>
				
			</div>
		</div>
	</div>

   <?php } 
   wp_reset_postdata();

   $response = ob_get_contents();
   ob_end_clean();

   echo $response;
   die(1);
   }


add_action( 'wp_ajax_nopriv_save_fundraisers', 'func_save_fundraisers_onAjaxAddtoCart' );
add_action( 'wp_ajax_save_fundraisers', 'func_save_fundraisers_onAjaxAddtoCart' );
function func_save_fundraisers_onAjaxAddtoCart(){
	$current_user = wp_get_current_user();
	$fundraisersName = $_POST[ 'name' ];
    $currentUserID = $current_user->ID;
    if($currentUserID){
		$get_current_funfraiser = get_user_meta( $currentUserID, 'repr_conn_fundr', $fundraisersName );
		if($get_current_funfraiser){
			update_user_meta( $currentUserID, 'repr_conn_fundr', $fundraisersName ); // update the meta			
		}else{
			add_user_meta( $currentUserID, 'repr_conn_fundr', $fundraisersName ); // add the meta
		}
	}
    
   echo $fundraisersName;
   die();
}

if ( ! function_exists('func_add_fundraisers_to_order') ) {
    add_action( 'woocommerce_checkout_create_order', 'func_add_fundraisers_to_order', 20, 1 );
    function func_add_fundraisers_to_order( $order ) {
		$current_user = wp_get_current_user();
		$currentUserID = $current_user->ID;
		$get_current_funfraiser = get_user_meta( $currentUserID, 'repr_conn_fundr', $fundraisersName );
        $order->update_meta_data('FunfraiserName', $get_current_funfraiser);        
    }
}
*/
/*
add_action( 'wp_ajax_nopriv_representative_pay', 'func_funfraiser_repre_pay' );
	add_action( 'wp_ajax_representative_pay', 'func_funfraiser_repre_pay' );
	function func_funfraiser_repre_pay(){
		global $wpdb;
		$fundID = $_POST['fund_id'];
		$get_referral = get_user_meta($fundID,'user_registration_referral_email',true);
        $referralUser = get_user_by( 'email', $get_referral );        
		$referralUserID = $referralUser->data->ID;
        
        if(get_user_meta($referralUserID,'reps_commission',true)){
         $get_referral_commission = get_user_meta($referralUserID,'reps_commission',true);       
        }else{
        $get_referral_commission = 0;
        }
        
		$args = array(
			'status'   => "published",
			'meta_key' => 'fundraiser_field',
			'meta_value' => $fundID
		);
		
		$orders = wc_get_orders($args);
		foreach ( $orders as $order ){
			
			$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
			$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
			$order_data = $order->get_data();
			$order_customer_id = $order_data['customer_id'];
			if ( $order->has_status('completed') || $order->has_status('processing')) 
			{
				
				$order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
				$extract_date=$order_date;
				$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
				$inactive_s=get_user_meta($frID3, "user_action_status", true);
				$in_s=1;
				if ($inactive_date < $extract_date && $inactive_s==2){
					$in_s=2;
				}
				else if($inactive_s==1 || empty($inactive_s)){
					$in_s=1;
				}
				else{
					$in_s=1;
				}
			
				if ($in_s ==1){
					if ($frID3 != $order_customer_id){
						$tdiscount=$order->get_total_discount();
						if(!empty($tdiscount)){
							$newamt=$order->get_subtotal()-$tdiscount;
							$total_sale_so_far+=$newamt;
						}else{
							$total_sale_so_far+=$order->get_subtotal();
						}
					}else if ($frID3 == $order_customer_id){
						$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
						if($order_type=="Online"){
							$tdiscount=$order->get_total_discount();

							if(!empty($tdiscount)){
								$newamt=$order->get_subtotal()-$tdiscount;
								$total_sale_so_far+=$newamt;
							}else{
								$total_sale_so_far+=$order->get_subtotal();
							}
						}

						if($order_type=="Catlog"){
										
							$order_item_quantity = 0; 
							foreach ($order->get_items() as $item_key => $item_values): 
								$item_id   = $item_values->get_id();
								$item_data = $item_values->get_data();
								$order_item_quantity += $item_data['quantity']; 										 
							endforeach;
							$qty= $order_item_quantity; 
							if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ){ 
								$subtot=$order->get_subtotal(); 
								$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
								$total_sale_so_far_Catlog+=$subtot-$to_widthdraw; 
							}  
							else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ){ 
								$subtot=$order->get_subtotal(); 
								$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
								$total_sale_so_far_Catlog+=$subtot-$to_widthdraw; 
							} 
							else
							{ 

							}									

						}
					}else{
					
					}
				}
			}
		
		}
		$firstName = get_user_meta( $fundID , 'first_name' , true );		
		$lastName = get_user_meta( $fundID , 'last_name' , true );								   
// 		$org = get_user_meta( $fundID , 'user_registration_input_company' , true );
$org = get_user_meta( $fundID , 'user_registration_name_of_school_business' , true );
        $totalAmount = $total_sale_so_far_Catlog + $total_sale_so_far;
		$get_referral_commission_amount = floatval ($totalAmount) * floatval ($get_referral_commission) / 100;
    	$representative_status = get_user_meta( $fundID , 'user_representative_status' , true );
        if($representative_status == 1){
        	$repStatus = "Paid";
        }else if($representative_status == 2){
        	$repStatus = "Unpaid";
		}else{
        	$repStatus = "Unpaid";
        }
        $final_totalAmount = floatval($totalAmount) - floatval($get_referral_commission_amount);
		echo "<table border='1'>
		<tr>
			<td>Fund ID: </td>
			<td>".$fundID."</td>
		</tr>
		<tr>
			<td>Director: </td>
			<td>".$firstName." ".$lastName."</td>
		</tr>
		<tr>
			<td>Organization: </td>
			<td>".$org."</td>
		</tr>
		<tr>
			<td>Total Online Sale</td>
			<td>$".number_format($total_sale_so_far, 2,'.','')."</td>
		</tr>
		<tr>
			<td>FUNDRAISER CATALOGUE SALES</td>
			<td>$".number_format($total_sale_so_far_Catlog, 2, '.', '')."</td>
		</tr>
		<tr>
			<td>Total (Total Online Sale + FUNDRAISER CATALOGUE SALES)</td>
			<td>$".number_format($total_sale_so_far_Catlog + $total_sale_so_far, 2, '.', '')."</td>
		</tr>
        
        <tr>
        <td>Referral commission in %</td>
        <td>".$get_referral_commission."%</td>
        </tr>
        
        <tr>
        <td>Referral commission in Amount</td>
        <td>$".$get_referral_commission_amount."</td>
        </tr>
        
        <tr>
        <td>Representative Status</td>
        <td>".$repStatus."</td>
        </tr>
		</table>";
		
	   die();
	}
add_action('wp_ajax_nopriv_representative_status', 'funct_representative_status'); // for not logged in users
add_action('wp_ajax_representative_status', 'funct_representative_status');
function funct_representative_status( )
{
	$ID = $_POST['fundId'];
	$action = $_POST['repre_action'];
	
	update_user_meta( $ID , 'user_representative_status' , $action );		
	wp_send_json_success(array(
	'action' => $action
	));
}
*/
/*function func_custom_checkbox_fields( $checkout ) {
    echo '<div class="cw_custom_class">';
    woocommerce_form_field( 'ship_with_catalog', array(
        'type'          => 'checkbox',
        'label'         => __('Ship with catalog'),
        'required'  => false,
    ), $checkout->get_value( 'ship_with_catalog' ));
    echo '</div>';
}

add_action('woocommerce_before_order_notes', 'func_custom_checkbox_fields'); 

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');
function custom_checkout_field_update_order_meta($order_id){
    if (!empty($_POST['ship_with_catalog'])) {
        update_post_meta($order_id, 'ship_with_catalog',$_POST['ship_with_catalog']);
    }
}

add_action('woocommerce_thankyou', 'func_custom_thankyou_change_order_status', 10, 1);
function func_custom_thankyou_change_order_status($order_id) {
    if (!$order_id)
        return;
    $order = wc_get_order($order_id);
    if (get_post_meta($order_id, 'ship_with_catalog', true) == 1){
        $order->update_status('wc-on-hold');
    } else {
        
    }
}*/

function the_dramatist_fire_on_wp_initialization() {

$users = get_users( array( 'fields' => array( 'ID' ) ) );
foreach($users as $user)
{
    $org=get_user_meta($user->ID,'user_registration_input_company' , true );
    if(!empty($org))    
    {
        
        update_user_meta($user->ID,'user_registration_name_of_school_business' ,$org);
    }
        
        
    }

//     $uid = 209918;
    
//     $user_info = get_userdata($uid);
//     $userdata = $user_info->data;
    
// $name_of_school_business = get_post_meta($uid,'user_registration_name_of_school_business', true );
// $input_box_phone = get_post_meta($uid,'user_registration_input_box_phone', true ); 
// $street_address = get_post_meta($uid,'user_registration_street_address', true );
// $City2 = get_post_meta($uid,'user_registration_city2', true ); 
// $select_state = get_post_meta($uid,'user_registration_select_state', true );
// $input_box_zip = get_post_meta($uid,'user_registration_input_box_zip', true );
// $fundraiser_preference = get_post_meta($uid,'user_registration_fundraiser_preference', true );
// $participants_no = get_post_meta($uid,'user_registration_participants_no', true );
// $start_date = get_post_meta($uid,'user_registration_start_date', true );
// $end_date = get_post_meta($uid,'user_registration_end_date', true ); 
// $first_name = get_post_meta($uid,'user_registration_first_name', true );
// $last_name = get_post_meta($uid,'user_registration_last_name', true );
// $input_box_1595312762 = get_post_meta($uid,'user_registration_input_box_1595312762', true );
// $chairpersonaddress = get_post_meta($uid,'user_registration_chairpersonaddress', true );
// $City = get_post_meta($uid,'user_registration_city', true );
// $State = get_post_meta($uid,'user_registration_state', true );
// $Zip_Code = get_post_meta($uid,'user_registration_zip_Code', true );
// $user_email = $userdata->user_email;
// $user_pass = 'admin@123';
// $Brochures_will_be_shipped = get_post_meta($uid,'user_registration_brochures_will_be_shipped', true );
// $radio_1534260773 = get_post_meta($uid,'user_registration_radio_1534260773', true );
// $reference = get_post_meta($uid,'user_registration_reference', true );
// $referral_name = get_post_meta($uid,'user_registration_referral_name', true );
// $referral_email = get_post_meta($uid,'user_registration_referral_email', true );
// $favorite_candle_scents = get_post_meta($uid,'user_registration_favorite_candle_scents', true );
// $fr_goal = get_post_meta($uid,'user_registration_fr_goal', true );
// $selling_goal = get_post_meta($uid,'user_registration_selling_goal', true );
// $reason_for_fundraising = get_post_meta($uid,'user_registration_reason_for_fundraising', true );
// $input_box_1693293590 = get_post_meta($uid,'user_registration_input_box_1693293590', true );
// $check_box_new = get_post_meta($uid,'user_registration_check_box_new', true );
// $image_url_profile = get_post_meta($uid,'user_registration_image_url_profile', true );


//     // Do stuff. Say we will echo "Fired on the WordPress initialization".
//     $message = '<div style="font-family: Arial, sans-serif; background: rgb(242, 242, 242); width: 100%; float: left;">
//   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background: rgb(255, 255, 255); margin: 40px 20px; width: 920px; overflow: hidden;">
//         <tbody><tr>
//              <td align="center" style="padding: 30px; text-align: center; background: rgb(51, 51, 51);">
// 				<h1 style="color: rgb(255, 255, 255); font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0px; text-align: left;"> Fundraiser Registered </h1>
// 		  	</td>
// 		</tr>
// 		 <tr style="width: 100%; padding: 25px; max-width: 1000px; margin: 0px auto; float: left;">
//           <td align="left" style="width: 875px; display: inline-block;">
// 	   <p>Hi '.$first_name.' '.$last_name.'</p>
// 	   <p style="color: red;"><b>!!PLEASE DO NOT DELETE THIS EMAIL!!</b></p>
// 		<p> Thank you for booking your GLOW SCENTED CANDLES fundraiser! We are excited to work with you &amp; your group while making the sale as successful, hassle-free and turnkey as possible.</p>
// 		<p style="color: rgb(0, 0, 0); text-decoration: underline;"><b>CATALOG SALES (if your group is doing online only, please disregard)</b></p>
// 		<p>Glow will ship your brochures along with kick off product to you within 2 weeks of your sale start date via UPS to the address given below. Tracking information will be provided once it ships. In the meantime, you can set your online account below.  </p>
      
// 		<p style="color: rgb(0, 0, 0); text-decoration: underline;"><b>ONLINE "DIRECTOR LOGIN":</b></p>
// 		<p>Click the following link <a href="https://glowbrandscandles.com/my-account/?email='.$user_email.'&amp;pass='.$user_pass.'" target="_blank">https://glowbrandscandles.com/my-account</a> to access the Director Login. </p> 
// 		<p><b>Login Details: </b></p>
//          <p><b>Email Address: </b> '.$user_email.' </p>
// 	     <p><b>Password: </b> admin@123 </p>
		 
// 		<p>Here you can-</p>
// 		<ul>
// 		<li>View your up-to-date sales</li>
// 		 <li>Invite sellers to register for accounts to track their own sales</li>
// 		 <li>Place your catalog order at the end of your fundraiser</li>
// 		 <li>View your total fundraiser profits</li>
// 		 </ul>
		
// 		 <p><b>Your Fundraiser ID: '.$uid.'</b></p>
// 		 		<p></p><h4>FUNDRAISING AGREEMENT</h4><p></p>	
		
// 		<p><b>School/Group Name: </b> '.$name_of_school_business .'</p>
// 		<p><b>School /Group Phone Number : </b> '.$input_box_phone.'</p>
// 		<p><b>School/Group Address : </b> '.$street_address.'</p>
// 		<p><b>City : </b>'.$City2.'</p>
// 		<p><b>State  : </b>'.$select_state.'</p>
// 		<p><b>Zip Code  : </b>'.$input_box_zip.'</p>
// 		<p><b>Number of Participants  : </b>'.$participants_no.'</p>
// 		<p><b>Select Fundraiser Type  : </b>'.$fundraiser_preference.'</p>
// 		<p><b>Date Sale Start  : </b>'.$start_date.'</p>
// 		<p><b>Date Sale Ends  : </b>'.$end_date.'</p>
		
// 		<p></p><h4>CHAIRPERSON’S INFORMATION</h4><p></p>	
		
// 		<p><b>First Name: </b>'.$first_name.'</p>
// 		<p><b>Last Name : </b>'.$last_name.'</p>
// 		<p><b>Chairperson’s Cell Phone Number : </b>'.$input_box_1595312762.'</p>
// 		<p><b>Chairperson’s Address : </b>'.$chairpersonaddress.'</p>
		
		
// 		<p><b>City  : </b>'.$City.'</p>
// 		<p><b>State  : </b>'.$State.'</p>
// 		<p><b>Zip Code  : </b>'.$user_email.'</p>
// 		<p><b>Chairperson’s Email Address  : </b>'.$user_email.'</p>
// 		<p><b>Create a Chairperson’s Password  : </b>admin@123</p>
// 		<p><b>Brochures will be shipped to School/Group or to the Chairperson?  : </b>'.$Brochures_will_be_shipped.'</p>
		
// 		<p></p><h4>DETAILS</h4><p></p>	
		
// 		<p><b>Have you had a GLOW Fundraiser before  : </b>'.$radio_1534260773.'</p>
// 		<p><b>How did you hear about GLOW : </b>'.$reference.'</p>
// 		<p><b>Name of Referral/Representative/Website : </b>'.$referral_name.'</p>
// 		<p><b>Referral/ Your Representative’s Email Address : </b>'.$referral_email.'</p>
// 		<p><b>What are your favorite candle scents : </b>'.$favorite_candle_scents.'</p>
// 		<p><b>Fundraising Sales Goal (Dollar Amount Earned) : </b>'.$fr_goal.'</p>
// 		<p><b>Selling Goal (# of items to be sold per participant) : </b>'.$selling_goal.'</p>
// 		<p><b>Reason for Fundraising (A message to be read by your customers for why you are fundraising/how the funds raised will be used.) : </b>'.$reason_for_fundraising.'</p>
// 		<p><b>Profit Check for Online Store will be payable to : </b>.'.$input_box_1693293590.'.</p>
	
// 		<p><b>Profile Link: <a href="https://glowbrandscandles.com/profile/'.$uid.'?reason_is='.$reason_for_fundraising.'" target="_blank">https://'.$user_email.'/profile/'.$uid.'</a></b></p>
		
// 		<ul>
//  	<li>Boost sales and reach everyone with or online shopping option. This is the perfect solution to out of town family &amp; friends. Customer pays for their order online via credit card, their order drop ships directly to the customer within 7 days.</li>
//  	<li>Glow issues (ONE) profit check to the fundraiser director for easy disbursing of profits/funds earned by seller. YES, sales are tracked by sellers, do not worry!</li>
//  	<li>Online sales earn 40% profits, due to shipping and direct drop ship. For the highest profits, we encourage the catalog sale.</li>
//  	<li>Fundraising Directors will receive a report of profits earned on the 15th of the month concluding their sale. Check will be distributed to the group name listed on this form. If different, please notify Glow.</li>
// 	</ul>
// 		<p style="font-size: 22px; color: green;"><b>Please note: Customer(s) must enter the 6 digit FUNDRAISER ID CODE or use the full fundid link for sales to be tracked. (Box is found top left of the website)</b></p>
// 		<p>Want to send the parents an email, text or facebook announcing GLOW with the website? Simply copy and paste the website along with the FUNDRAISING CODE and remind them to "shop away"</p>
// 		<p><b>If you have any questions at all during your sale please feel free to contact us, we are here to help!</b></p>
// 		<p>The Glow Team<font color="#888888"><br>Glow Scented Candles<br>
// <a href="mailto:contact@glowscentedcandles.com" target="_blank">contact@glowscentedcandles.com</a><br>
// <a href="https://glowbrandscandles.com" target="_blank">www.glowscentedcandles.com</a><u></u><u></u></font></p>
// 	  </td>
// 		</tr>
//         </tbody></table>
	
//     </div>';
//     $headers = array('Content-Type: text/html; charset=UTF-8');
//  wp_mail($user_email,'Fund Raising Registered', $message, $headers );
//  wp_mail($referral_email,'Fund Raising Registered', $message, $headers );
//  wp_mail('candacethompson12@gmail.com','Fund Raising Registered', $message, $headers );
//  wp_mail('webdevelopment8511@gmail.com','Fund Raising Registered', $message, $headers );
//  echo $message;
 
 die;
}
//add_action( 'init', 'the_dramatist_fire_on_wp_initialization' );

/* Send Check Referral/User Email */

function custom_new_order_email_send_check($order_id)
{
	$slrID = get_post_meta($order_id, 'fundraiser_field', true );
	$action_status = get_user_meta( $slrID , 'user_action_status' , true );
	/*
	if($action_status==1 || empty($action_status)) {
	
    */	
		
	$referral_email = get_user_meta($slrID, 'user_registration_referral_email', true );
	$group_name = get_user_meta( $slrID, 'user_registration_name_of_school_business', true );
	$orderType = get_post_meta( $order_id, 'wc_order_type', true );
	
	if($orderType=="Catlog")
	{
		$checkbox=get_post_meta( $order_id, 'wc_checkbox_field', true );
		$slr_info  = get_userdata($slrID);
		$user_email = $slr_info->user_email;
	
		if(!empty($referral_email))
		{
			$multiple_recipients = array(
						$referral_email
					);
		}
		else 
		{
			$multiple_recipients="";
		}
		
	
		$subject = "Glow Scented Candles - Brochure Order (# ".$order_id.")";
		$heading = "Glow Scented Candles - Brochure Order #".$order_id;
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
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="600px" bgcolor="#fff" style="background-color:#f7f7f7;margin:0 auto;padding:70px 0 70px 0;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
			   <img style="width: 180px;" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/10/unnamed-1.png">
				
		  	</td>
		</tr>
		 <tr>
           <td align="left" style="padding:48px 48px 0; background:#fff;">
		   <h1 style="color: #333;font-size: 25px;font-weight: 600;line-height: 100%;padding-bottom: 10px;"> <?php echo $heading; ?> </h1>
		   <p style="margin:0 0 16px">Your order has been received and is now being processed. Your order
			details are shown below for your reference:</p>
			<h2>
				<?php
				global $woocommerce;
				if ( !$order_id )
				return;
				$order = wc_get_order( $order_id );
				$text_align='center';
				echo wp_kses_post( $before . sprintf( __( 'Order #%s', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
				?>
			</h2>

			<div style="margin-bottom: 40px;">
				<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
					<thead>
						<tr>
							<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
							<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
							<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
					
					<?php 
					
						$text_align = is_rtl() ? 'right' : 'left';
						global $woocommerce;
						$id=$order_id;
						$order = new WC_Order( $id );
						$items = $order->get_items();

						/*Seller name */
						$seller_name= array();
						foreach ( $items as $item_id => $item ) :
						$s_name=$item['_wdm_seller_name'];
						if(!in_array($s_name,$seller_name))
						{
							$seller_name[]=$s_name;
						}
						endforeach; 
						if(empty($seller_name)) 
						{
							$order_type="Online";
						}
						else
						{
							$order_type="Catlog";
						}	
						if($order_type=="Catlog")
						{
							for($i=0;$i<count($seller_name);$i++)
							{	
								foreach ( $items as $item_id => $item ) :
									$product       = $item->get_product();
									$sku           = '';
									$purchase_note = '';
									$image         = '';

									if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
										continue;
									}

									if ( is_object( $product ) ) {
										$sku           = $product->get_sku();
										$purchase_note = $product->get_purchase_note();
										$image         = $product->get_image( $image_size );
										$price=$product->get_price();
									}
							$s_name=$item['_wdm_seller_name'];
				?>
				<?php if($seller_name[$i]!=$title2){ ?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
												<td  class="td"><?php echo '<b>Seller Name: '.$seller_name[$i].'</b><br />';?></td>
												<td  class="td"></td>
												<td  class="td"></td>
												
											</tr>
				<?php  } $title2 = $seller_name[$i];
				if($s_name==$seller_name[$i]){
				?>
				<tr id="order_processed_email" class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
					<?php

					// Show title/image etc.
					if ( $show_image ) {
						echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
					}

					// Product name.
					echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

					// SKU.
					if ( $show_sku && $sku ) {
						echo wp_kses_post( ' (#' . $sku . ')' );
					}

					// allow other plugins to add additional product information here.
					do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

					wc_display_item_meta( $item );

					// allow other plugins to add additional product information here.
					do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

					?>
					</td>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?>
					</td>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
						 <?php echo "$".$price; ?>
					</td>
				</tr>
				<?php }

				if ( $show_purchase_note && $purchase_note ) {
					?>
					<tr>
						<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
							<?php
							echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
							?>
						</td>
					</tr>
					<?php
				}
				?>

			<?php endforeach; } }
			else {

			foreach ( $items as $item_id => $item ) :
				$product       = $item->get_product();
				$sku           = '';
				$purchase_note = '';
				$image         = '';

				if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					continue;
				}

				if ( is_object( $product ) ) {
					$sku           = $product->get_sku();
					$purchase_note = $product->get_purchase_note();
					$image         = $product->get_image( $image_size );
					$price=$product->get_price();
				}

				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
					<?php

					// Show title/image etc.
					if ( $show_image ) {
						echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
					}

					// Product name.
					echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

					// SKU.
					if ( $show_sku && $sku ) {
						echo wp_kses_post( ' (#' . $sku . ')' );
					}

					// allow other plugins to add additional product information here.
					do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

					wc_display_item_meta( $item );

					// allow other plugins to add additional product information here.
					do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

					?>
					</td>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?>
					</td>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
						<?php echo "$".$price; ?>
					</td>
				</tr>
				<?php

				if ( $show_purchase_note && $purchase_note ) {
					?>
					<tr>
						<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
							<?php
							echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
							?>
						</td>
					</tr>
					<?php
				}
				?>
			<?php endforeach;  } ?>
					</tbody>
					<tfoot>
			
								<?php
						/* Ship to School Order QTY */
						 
						 $item_qty_fund_online=0;
						
						  /* Start Ship to school qty calculation */
						  $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $slrID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order_new ) {
								$frID3 = get_post_meta($order_new->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order_new->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order_new->ID, 'ship_with_catalog', true );
								$order_new_data = $order_new->get_data();
                                $order_customer_id = $order_new_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = $order_new->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order_new->ID, 'wc_order_type', true );
                            if ($order_type=='ship-to-school') {
								    foreach ($order_new->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order_new->ID, 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															else{}
                                             endforeach; 
                         } } } }
						}
						
				
										$total_cat_quantity =0; 						
										$total_sale_so_far_catlog=0;
										$profit_catlog=0;
										$total_sales_subtot=0;
										$total_sale_so_far_catlog_deduct=0;
										$profit_catlog_a=0;
					
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
										    
										$qty = $total_cat_quantity+$item_qty_fund_online;  
										$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
										/* Start Online Deduction Calculation */
										if($glow_profit_deduct=="Yes")
										{
											    $glow_profit_deduct_amt=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey',true);
												$total_sale_so_far_catlog_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey_rem',true);
												$tot=$order->get_total(); 
												$shipping_cost=$order->get_shipping_total();
												if($tot==0)
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;  //Profit
														
													    $sale_total = $subtot - $profit_catlog;   //Amt Due
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													} 
													else
													{ 
									
													}
												}
												else
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													} 
													else
													{ 
									
													}
												}
										$glow_profit_deduct_metakey_totalRebate=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey_totalRebate',true); 	
										$glow_profit_deduct_amt_online_totalsales=get_post_meta( $order->get_id(),'glow_profit_deduct_metakey_totalOnlineSales',true);	
										$curr_balance=0;
										$total_sale_so_far_catlog = number_format($total_sale_so_far_catlog, 2, '.', '');	
										$curr_balance=(float)$total_sale_so_far_catlog-(float)$glow_profit_deduct_metakey_totalRebate;
										$c_symbole="";
										if($glow_profit_deduct_metakey_totalRebate>$total_sale_so_far_catlog)
										{
											$c_symbole = "-";
										}
										//$curr_balance=get_user_meta( $ID,'user_rebate_remain_glowProfitDeduct', true);
										$curr_balance=abs($curr_balance);
										$curr_balance = number_format($curr_balance, 2, '.', '');
										
										$item_totals = $order->get_order_item_totals();
										$sort_array=array();
										if ( $item_totals ) {
											foreach ( $item_totals as $total ) {
												 if($total['label']=="Shipping:"){ 
													$sort_array[0]=array("label"=>"Shipping:","value"=>$total['value']);
												}
												 if($total['label']=="Payment method:"){ 
													$sort_array[1]=array("label"=>"Payment method:","value"=>$total['value']);
												}
												 if($total['label']=="Subtotal:"){ 
													$sort_array[2]=array("label"=>"Brochure Total Sales:","value"=>$total['value']);
												}
												if($total['label']=="Fundraiser Discount:"){
													$sort_array[3]=array("label"=>"Brochure Organization Profit:","value"=>$total['value']);
												}
												if($total['label']=="Online Profits Deduction:"){ 
												    $sort_array[8]=array("label"=>"Online Store Profit:","value"=>$total['value']);
											    }
											 }
										}											 
											if(!empty($glow_profit_deduct_amt_online_totalsales)){ 
												$full_onine_sales="$".$glow_profit_deduct_amt_online_totalsales;
												$sort_array[5]=array("label"=>"Online Store Total Sales:","value"=>$full_onine_sales);
											}
											else{
												$full_onine_sales="$0.00";
												$sort_array[5]=array("label"=>"Online Store Total Sales:","value"=>$full_onine_sales);
											}
											if(!empty($total_sale_so_far_catlog)){ 
													$full_val="$".$total_sale_so_far_catlog;
													$sort_array[4]=array("label"=>"Brochure Amount Due to Glow:","value"=>$full_val);
										    }
											else{
												$full_val="$0.00";
												$sort_array[4]=array("label"=>"Brochure Amount Due to Glow:","value"=>$full_val);
											}
											if(!empty($glow_profit_deduct_metakey_totalRebate)){ 
												$full_val2="$".$glow_profit_deduct_metakey_totalRebate;
												$sort_array[6]=array("label"=>"Online Store Profit:","value"=>$full_val2);
										    }
											else{
												$full_val2="$0.00";
												$sort_array[6]=array("label"=>"Online Store Profit:","value"=>$full_val2);
											}
											if(!empty($total_sale_so_far_catlog)){ 
												$full_val4="$".$total_sale_so_far_catlog;
												$sort_array[7]=array("label"=>"Brochure Amount Owed:","value"=>$full_val4);
										    }
											else{
												$full_val4="$0.00";
												$sort_array[7]=array("label"=>"Brochure Amount Owed:","value"=>$full_val4);
											}
											if(!empty($curr_balance)){ 
											        $full_str="Current Balance:<br/>If minus (-) is shown Glow will be issuing your organization a check.";
													$full_val3=$c_symbole."$".$curr_balance;
													$sort_array[9]=array("label"=>$full_str,"value"=>$full_val3);
										   }
										   else{
												$full_str="Current Balance:<br/>If minus (-) is shown Glow will be issuing your organization a check.";
												$full_val3=$c_symbole."$0.00";
												$sort_array[9]=array("label"=>$full_str,"value"=>$full_val3);
											}
						?>
						<?php
						$i=0;
						for( $q=0; $q<10; $q++) {
							$i++;
							if(!empty($sort_array[$q]['label']))
							{
							?>
								<tr style="">
									<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $sort_array[$q]['label']; ?></th>
									<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $sort_array[$q]['value']; ?></td>
								</tr>
						<?php	} } 		
									
						
				}	
				/* End Online Deduction Calculation */
				else
				{
									$item_totals = $order->get_order_item_totals();

									if ( $item_totals ) {
										$i = 0;
										foreach ( $item_totals as $total ) {
											$i++;
											?>
											<tr>
												<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
												<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
											</tr>
											<?php
										}
									}
				}		  
			
			
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
				</table>
				<h3>Additional information</h3>
				<table>
					<tr>
						<td>Are you purchasing to support a fundraiser?: </td>
						<td><?php echo wptexturize( get_post_meta( $order->id, 'wc_checkbox_field', true ) ); ?> </td>
					</tr>
					<?php if( $checkbox=="Yes") { ?>
					<tr>
						<td>Fundraiser ID: </td>
						<td><?php echo wptexturize( $slrID ); ?></td>
					</tr>
					<tr>
						<td>Group Name: </td>
						<td><?php echo wptexturize( $group_name ); ?></td>
					</tr>
					<?php } ?>
				</table>
				<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
				<tr>
					<td style="text-align:<?php echo $text_align; ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
						<h2><?php _e( 'Billing address', 'woocommerce' ); ?></h2>

						<address class="address">
							<?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
							<?php if ( $order->get_billing_phone() ) : ?>
								<br/><?php echo esc_html( $order->get_billing_phone() ); ?>
							<?php endif; ?>
							<?php if ( $order->get_billing_email() ) : ?>
								<p><?php echo esc_html( $order->get_billing_email() ); ?></p>
							<?php endif; ?>
						</address>
					</td>
					<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>
						<td style="text-align:<?php echo $text_align; ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
							<h2><?php _e( 'Shipping address', 'woocommerce' ); ?></h2>

							<address class="address"><?php echo $shipping; ?></address>
						</td>
					<?php endif; ?>
				</tr>
			</table>
		</div>
        </td>
		</tr>
    </table>
</div>
</body>
</html>
<?php 
	$body= ob_get_clean();
	$m_body=stripslashes($body);
	if ( $order && $order->get_payment_method() === 'cheque' ) {
		$check_info = '<p style="font-family:Arial,sans-serif;font-size:14px;line-height:1.6;max-width:600px;margin:20px auto;">' . nl2br(esc_html(glow_check_payment_instructions())) . '</p>';
		$m_body = str_replace('</body>', $check_info . '</body>', $m_body);
	}
   	$headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);
//    	$admin_email = array(
//     "candace@glowscentedcandles.com",
//     "candacethompson12@gmail.com",
//     "hamzatechypros105@gmail.com"
// );
   	$admin_email = array_filter(array(
    "candace@glowscentedcandles.com",
    "candacethompson12@gmail.com",
    $user_email ?? '',
    $referral_email ?? ''
));
   
//hamzawork


				
						$order = new WC_Order( $order_id );
						 	$slrID = get_post_meta($order_id, 'fundraiser_field', true );
							$action_status = get_user_meta( $slrID , 'user_action_status' , true );
						
							$referral_email = get_user_meta($slrID, 'user_registration_referral_email', true );
							$group_name = get_user_meta( $slrID, 'user_registration_name_of_school_business', true );
							$orderType = get_post_meta( $order_id, 'wc_order_type', true );
						 	$item_qty_fund_online=0;
						
						  /* Start Ship to school qty calculation */
						  $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $slrID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order_new ) {
								$frID3 = get_post_meta($order_new->ID, 'fundraiser_field', true );
								$ID3 = get_post_meta($order_new->ID, 'wc_seller_field', true );
							   	$scat = get_post_meta($order_new->ID, 'ship_with_catalog', true );
								$order_new_data = $order_new->get_data();
                                $order_customer_id = $order_new_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = $order_new->order_date;
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
									if ($in_s ==1){
								  if ($frID3 != $order_customer_id) { 
								  $order_type = get_post_meta($order_new->ID, 'wc_order_type', true );
                            		if ($order_type=='ship-to-school') {
								    foreach ($order_new->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
												$order_type_2 = get_post_meta($order_new->ID, 'wc_order_type', true );
															if($order_type_2=="ship-to-school") 
															{
																$item_qty_fund_online +=  $item_data['quantity']; 
															}
															
															
                                             endforeach; 
                         	} } } }
						}
						
				
										$total_cat_quantity =0; 						
										$total_sale_so_far_catlog=0;
										$profit_catlog=0;
										$total_sales_subtot=0;
										$total_sale_so_far_catlog_deduct=0;
										$profit_catlog_a=0;
					
										 foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data();
												$total_cat_quantity += $item_data['quantity']; 			//ship to school								
										  endforeach;	
										    
										$qty = $total_cat_quantity+$item_qty_fund_online;  
										$glow_profit_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_yes',true);
										/* Start Online Deduction Calculation */
										
											    $glow_profit_deduct_amt=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey',true);
												$total_sale_so_far_catlog_deduct=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey_rem',true);
												$tot=$order->get_total(); 
												$shipping_cost=$order->get_shipping_total();
												if($tot==0)
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot; // Total Brochure Sales
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;  //Profit
														
													    $sale_total = $subtot - $profit_catlog;   //Amt Due
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
														
													} 
													
												}
												else
												{
													if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order) ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (40) / 100; 
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													}  
													else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
													{ 
														$subtot=$order->get_subtotal(); 
														$total_sales_subtot=$subtot;
														
														$profit_catlog = floatval ($subtot) * floatval (50) / 100;
														
														$profit_catlog_a = $profit_catlog;
														
														$sale_total = $subtot - $profit_catlog;
														if($shipping_cost>0)
														{
															 $sale_total_new = $sale_total + $shipping_cost;
														}
														else
														{
															$sale_total_new = $sale_total;
														}
														//$glow_sale_total=$sale_total-$glow_profit_deduct_amt;
														$total_sale_so_far_catlog=$sale_total_new; 
													} 
													
												}
										$glow_profit_deduct_metakey_totalRebate=get_post_meta( $order->get_id(), 'glow_profit_deduct_metakey_totalRebate',true); 	
										$glow_profit_deduct_amt_online_totalsales=get_post_meta( $order->get_id(),'glow_profit_deduct_metakey_totalOnlineSales',true);	
										$curr_balance=0;
										$total_sale_so_far_catlog = number_format($total_sale_so_far_catlog, 2, '.', '');	
										$curr_balance=(float)$total_sale_so_far_catlog-(float)$glow_profit_deduct_metakey_totalRebate;
										$c_symbole="";
										if($glow_profit_deduct_metakey_totalRebate>$total_sale_so_far_catlog)
										{
											$c_symbole = "-";
										}
										//$curr_balance=get_user_meta( $ID,'user_rebate_remain_glowProfitDeduct', true);
										$curr_balance=abs($curr_balance);
										$curr_balance = number_format($curr_balance, 2, '.', '');
										
										$item_totals = $order->get_order_item_totals();
										$sort_array=array();
										if ( $item_totals ) {
											foreach ( $item_totals as $total ) {
												 if($total['label']=="Shipping:"){ 
													$sort_array[0]=array("label"=>"Shipping:","value"=>$total['value']);
												}
												 if($total['label']=="Payment method:"){ 
													$sort_array[1]=array("label"=>"Payment method:","value"=>$total['value']);
												}
												 if($total['label']=="Subtotal:"){ 
													$sort_array[2]=array("label"=>"Brochure Total Sales:","value"=>$total['value']);
												}
												if($total['label']=="Fundraiser Discount:"){
													$sort_array[3]=array("label"=>"Brochure Organization Profit:","value"=>$total['value']);
												}
												if($total['label']=="Online Profits Deduction:"){ 
												    $sort_array[8]=array("label"=>"Online Store Profit:","value"=>$total['value']);
											    }
											 }
										}											 
											if(!empty($glow_profit_deduct_amt_online_totalsales)){ 
												$full_onine_sales="$".$glow_profit_deduct_amt_online_totalsales;
												$sort_array[5]=array("label"=>"Online Store Total Sales:","value"=>$full_onine_sales);
											}
											else{
												$full_onine_sales="$0.00";
												$sort_array[5]=array("label"=>"Online Store Total Sales:","value"=>$full_onine_sales);
											}
											if(!empty($total_sale_so_far_catlog)){ 
													$full_val="$".$total_sale_so_far_catlog;
													$sort_array[4]=array("label"=>"Brochure Amount Due to Glow:","value"=>$full_val);
										    }
											else{
												$full_val="$0.00";
												$sort_array[4]=array("label"=>"Brochure Amount Due to Glow:","value"=>$full_val);
											}
											if(!empty($glow_profit_deduct_metakey_totalRebate)){ 
												$full_val2="$".$glow_profit_deduct_metakey_totalRebate;
												$sort_array[6]=array("label"=>"Online Store Profit:","value"=>$full_val2);
										    }
											else{
												$full_val2="$0.00";
												$sort_array[6]=array("label"=>"Online Store Profit:","value"=>$full_val2);
											}
											if(!empty($total_sale_so_far_catlog)){ 
												$full_val4="$".$total_sale_so_far_catlog;
												$sort_array[7]=array("label"=>"Brochure Amount Owed:","value"=>$full_val4);
										    }
											else{
												$full_val4="$0.00";
												$sort_array[7]=array("label"=>"Brochure Amount Owed:","value"=>$full_val4);
											}
											if(!empty($curr_balance)){ 
											        $full_str="Current Balance:<br/>If minus (-) is shown Glow will be issuing your organization a check.";
													$full_val3=$c_symbole."$".$curr_balance;
													$sort_array[9]=array("label"=>$full_str,"value"=>$full_val3);
										   }
										   else{
												$full_str="Current Balance:<br/>If minus (-) is shown Glow will be issuing your organization a check.";
												$full_val3=$c_symbole."$0.00";
												$sort_array[9]=array("label"=>$full_str,"value"=>$full_val3);
											}

// if ( strpos( $full_val3, '-' ) !== false ) {

//     $to      = get_option( 'admin_email' ); // You can change to any email
//     $subject = 'Negative Balance Alert';
//     $message = '<p>The user/order has a negative balance.</p>';
//     $message .= '<p>Balance: ' . $full_val3 . '</p>';
//     $headers = array( 'Content-Type: text/html; charset=UTF-8' );

//     wp_mail( $to, $subject, $message, $headers );
// }
									$billing_address = $order->get_formatted_billing_address() ? $order->get_formatted_billing_address() : __( 'N/A', 'woocommerce' );
									$billing_phone   = $order->get_billing_phone() ? $order->get_billing_phone() : '';
									$billing_email   = $order->get_billing_email() ? $order->get_billing_email() : '';

									// Get the shipping address as a string (if needed)
									$shipping_address = '';
									if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) {
									    $shipping_address = $order->get_formatted_shipping_address() ? $order->get_formatted_shipping_address() : __( 'N/A', 'woocommerce' );
									}
						$i=0;
						$td='';
						for( $q=0; $q<10; $q++) {
							$i++;
							if(!empty($sort_array[$q]['label']))
							{
								$td.='<tr style="padding-top: 20px;">
			                      <td style="font-size:11px;">'.$sort_array[$q]['label'].'</td>
			                      <td style="font-size:11px;">'.$sort_array[$q]['value'].'</td>
			                    </tr>';
								} 
						} 		
					


if ( ! class_exists( 'TCPDF' ) ) {
	foreach ( array(
		GLOW_FC_DIR . 'TCPDF-main/tcpdf.php',                                        // bundled copy (if present)
		WP_PLUGIN_DIR . '/user-registration-pro/vendor/tecnickcom/tcpdf/tcpdf.php',  // vendored TCPDF already on site
	) as $glow_tcpdf_path ) {
		if ( is_file( $glow_tcpdf_path ) ) { require_once $glow_tcpdf_path; break; }
	}
}
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 14, '', true);
$pdf->AddPage();
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Invoice Email</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

</head>
<body>
  <center style="width:100%; background:#f4f4f4; padding:24px 0;">
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr class="dis-flex">
              <td class="header" style="padding:20px; width: 180px; ">
                <img src="{$GLOBALS['glow_dynamic_url_bases']['uploads']}2025/11/logopdf.png" alt="Company Logo" class="logo" />     
              </td> 
              <td class="header" style="padding:20px; font-size: 14px;">
                    DATE:$order_date<br>                            
                    INVOICE #:$order_id<br>  
        			FUNDRAISER ID #:$slrID
     
              </td>        
            </tr>
          </table>
          <tr><td></td></tr>
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #eee;">
            <tr>
              <td class="section" style="padding-bottom:10px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="two-col">
                  <tr>
                    <td class="col col-25" style="padding-right:12px;">
                     <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial, sans-serif; border-collapse: collapse;">
					    <tr>
					        <td style="background:#f4f4f4; padding:20px 15px; font-size:16px; font-weight:bold;border: 1px solid #c3c3c3;">
					            Bill To:
					        </td>
					    </tr>
					    <tr>
					        <td style="padding: 15px 15px 0px 15px; font-size:14px; font-weight:500; border-right: 1px solid #c3c3c3;
    border-left: 1px solid #c3c3c3; ">
					            $billing_address<br>
					            $billing_phone<br>
					            $billing_email
					        </td>
					    </tr>

					</table>

                    </td>
                     <td class="col col-25" style="padding-right:12px;">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial, sans-serif; border-collapse: collapse;">
					    <tr>
					        <td style="background:#f4f4f4; padding:20px 15px; font-size:16px; font-weight:bold;border: 1px solid #c3c3c3;">
					            Ship To:
					        </td>
					    </tr>
					    <tr>
					        <td style="padding:20px 15px 20px 15px; font-size:14px; font-weight:500; border-right: 1px solid #c3c3c3;
    border-left: 1px solid #c3c3c3;border-bottom: 1px solid #c3c3c3;">
					            $shipping_address
					        </td>
					    </tr>
					</table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
        

		<tr><td></td></tr>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="section" style="padding-top:0;">
            <tr>
              <td style="padding:20px;">
                <table role="presentation" class="product-table" width="100%" cellpadding="0" cellspacing="0">         
                  <tbody>
                      $td
                  </tbody>
                </table>
        </td>
      </tr>
    </table>
<tr><td></td></tr>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial, sans-serif; border-collapse: collapse;">
		<tr>
			<td style="padding:20px; padding-top:0px;">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial, sans-serif; border-collapse: collapse; padding:20px; font-weight: 200;">
					<tr>
						<td style="font-size:14px; display: b">
							Remit organization/business check or money order payable to Glow Brands LLC.
						</td>
						</tr>
					
						<tr>
						<td style="font-size:14px;">
							Personal checks/temporary checks are not accepted.  
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;">
							Mail payment to Glow Brands LLC
						</td>
					</tr>
				
					<tr>
						<td style="font-size:14px;">
							3702 Cavalier Drive
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;">
							Suite 100
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;">
							Garland TX 75042
						</td>
					</tr>
					<tr><td></td></tr>
					<tr>
						<td style="font-size:14px;">
							Orders are processed following receipt of payment.  
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;">
							Late orders are accepted and processed as new orders.   
						</td>
					</tr>

					<tr><td></td></tr>
					<tr>
						<td style="font-size:14px;">
							A purchase order for public schools can be accepted, requires the amount to be paid, the name and address of GLOW Scented Candles. If a vendor application, W-9, or other forms are required, all documents and terms must be completed and agreed upon prior to submitting your purchase order. Email a scanned copy of your purchase order to contact@glowscentedcandles.com. A purchase order number only is not accepted.   
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;">
							Purchase Orders are accepted with a 15-day payment term upon receipt of products or by December 31st, whichever is sooner and are subject to late fees. Upon receipt of your order, remit payment by a school check or money order, payable to Glow Brands LLC along with a copy of your purchase order and invoice. A new purchase order will be required if a late order is submitted.
						</td>
					</tr>

					<tr><td></td></tr>
					<tr>
						<td style="font-size:14px;">
							Orders are packed and shipped following receipt of payment or purchase order.   
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;">
							Questions? Contact Glow
							www.glowscentedcandles.com
							Email: contact@glowscentedcandles.com
   
						</td>
					</tr>
					<tr>
						<td style="font-size:14px;">
							Thank you for the opportunity to help you raise money for your organization.   
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
  </center>
</body>
</html>
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$wp_uploads = wp_upload_dir();
$pdf_file = $wp_uploads['basedir'] . '/order-invoice.pdf';

// Save the PDF to server
$pdf->Output($pdf_file, 'F');
$attachments = array($pdf_file);

 if (wp_mail($admin_email, $subject, $m_body, $headers, $attachments)) {
      error_log("email has been successfully sent to user whose email is " . $multiple_recipients);
    }
	else {
      error_log("email failed to sent to user whose email is " . $multiple_recipients);
    }







}
/* } */

}

//add_action( 'woocommerce_checkout_order_processed', 'custom_new_order_email_send_check',1,1); 
add_action('woocommerce_order_status_processing', 'custom_new_order_email_send_check');
add_action('woocommerce_order_status_on-hold', 'custom_new_order_email_send_check');

/* Ship To School Process Coding */

// ADDING 2 NEW COLUMNS WITH THEIR TITLES (keeping "Total" and "Actions" columns at the end)
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column($columns)
{
    $reordered_columns = array();

    // Inserting columns to a specific location
    foreach( $columns as $key => $column){
        $reordered_columns[$key] = $column;
        if( $key ==  'order_status' ){
            // Inserting after "Status" column
            $reordered_columns['fundr_ID'] = __( '#FID','theme_domain');
          
        }
    }
    return $reordered_columns;
}

// Adding custom fields meta data for each new column (example)
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
function custom_orders_list_column_content( $column, $post_id )
{
    switch ( $column )
    {
        case 'fundr_ID' :
            // Get custom post meta data
            $my_var_one = get_post_meta( $post_id, 'fundraiser_field', true );
			 $order_type = get_post_meta( $post_id, 'wc_order_type', true );
			 if($order_type=="ship-to-school")
			 {
					if(!empty($my_var_one))
						echo '<strong style="color:#2271b1;">#'.$my_var_one.'</strong>';
					else
						echo '<small><em>-</em></small>';
			 }
			 else
			 {
				 echo '<small><em>-</em></small>';
			 }
            break;

    }
}

// Display the custom checkbow field in checkout
add_action( 'woocommerce_review_order_before_order_total', 'fee_installment_checkbox_field', 20 );
function fee_installment_checkbox_field(){
    if (glow_checkout_ship_to_group_blocked() || !glow_ship_to_group_customer_allowed()) { WC()->session->set('installment_fee', 0); return; }
	$user = wp_get_current_user();
	if(!is_user_logged_in() || in_array( 'seller', (array) $user->roles ))
	{
		echo '<tr class="packing-select"><th>';

		woocommerce_form_field( 'installment_fee', array(
			'type'          => 'checkbox',
			'class'         => array('installment-fee form-row-wide'),
			'label'         => __('Ship to School/Group Order'),
			'placeholder'   => __(''),
		), WC()->session->get('installment_fee') ? '1' : '' );

		echo '</th><td></td></tr>';
	}
}

// jQuery - Ajax script
add_action( 'wp_footer', 'checkout_fee_script' );
function checkout_fee_script() {
    // Only on Checkout
	global $woocommerce;

    if( is_checkout() && ! is_wc_endpoint_url() ) :

    ?>
    <script type="text/javascript">
	 
    jQuery( function($){
		// $('.checkout.woocommerce-checkout .col-lg-5.col-md-5 #order_review .shop_table .woocommerce-shipping-totals.shipping #shipping_method li:nth-child(2)').addClass("hide");
        if (typeof wc_checkout_params === 'undefined')
            return false;
		
        $('form.checkout').on('change', '#fundraiser_field', function(){ $('body').trigger('update_checkout'); });
        $('form.checkout').on('change', 'input[name=installment_fee]', function(){
            var fee = $(this).prop('checked') === true ? '1' : '0';

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: {
                    'action': 'installment_fee',
                    'installment_fee': fee,
                },
                success: function (result) {
					console.log(result);
					var a="<?php echo $amount = WC()->cart->cart_contents_total; ?>";
					console.log(a);
					//alert(result.data.action);
					$('body').trigger('update_checkout');
					
					setTimeout(function() {
                        $('body').trigger('update_checkout');
                    }, 2500);
				},
            });
        });
		
    });
    </script>
	
	<style>
	.packing-select p#installment_fee_field {
		margin: 0;
		color: #000;
	}
	</style>
	
	<?php
    endif;
	
	 if(is_cart()):
	 ?>
	<script type="text/javascript">
	 
		jQuery( function($){
		  $('.actions input[name=update_cart]').on('click', function () {
				//	$('body').trigger('wc_fragment_refresh');
				//	$('body').trigger('updated_cart_totals');
				 // jQuery('body').trigger('update_checkout');
			//	 $(document.body).trigger('wc_fragment_refresh');
					console.log("clickkkkkk");
					setTimeout(function() {
						// location.reload();
					//	$(document.body).trigger('wc_fragment_refresh');
						//  jQuery('body').trigger('update_checkout');
                   //   $('body').trigger('wc_fragment_refresh');
				//   	$('body').trigger('updated_cart_totals');
                    }, 2500);
					
		 });

		});
    </script>
	 <?php
	  endif;
	 
}

// Get Ajax request and saving to WC session
add_action( 'wp_ajax_installment_fee', 'get_installment_fee' );
add_action( 'wp_ajax_nopriv_installment_fee', 'get_installment_fee' );
function get_installment_fee() {
    if ( isset($_POST['installment_fee']) ) {
        WC()->session->set('installment_fee', ($_POST['installment_fee'] ? 1 : 0) );
		$s=WC()->session->get('installment_fee');
		wp_send_json_success(array(
				'action' => $s));
    }
	 //  die();
}

// Add a custom calculated fee conditionally
add_action( 'woocommerce_cart_calculate_fees', 'set_installment_fee' );
function set_installment_fee( $cart ){
    if ( is_admin() && ! defined('DOING_AJAX') || ! is_checkout() )
        return;

    if (!glow_fc_cart_is_catalog() && glow_ship_to_group_customer_allowed() && !glow_checkout_ship_to_group_blocked() && WC()->session->get('installment_fee') == 1) {
        $cart->add_fee(__('Processing Fee'), 1.95);
    }
	if (session_status() === PHP_SESSION_NONE && !headers_sent()) { @session_start(); }
    global $woocommerce;
	$user = wp_get_current_user();  
	if(glow_fc_cart_is_catalog())
	{
		if( in_array( 'fundraiser', (array) $user->roles ) ) 
		{
			$ID = $user->ID;
			$glow_profit_deduct=get_user_meta( $ID,'glow_profit_deduct_user_metakey_yes', true);
			if(empty($glow_profit_deduct))
			{
				$glow_profit_deduct_amt=get_user_meta( $ID,'glow_profit_deduct_save_oncheckout_page', true);
				if($glow_profit_deduct_amt>0)
				{
					$woocommerce->cart->add_fee("Online Profits Deduction", (-1 * $glow_profit_deduct_amt), true, ' ');
				}
		   }
		}
	}	
}

// Cart Page shipping - update cart data
add_action( 'woocommerce_after_cart_contents', 'woo_custom_shipping_method' );
function woo_custom_shipping_method()
{
    if (glow_fc_cart_is_catalog()) return;
	
	unset( WC()->session->chosen_shipping_methods );
	$amount = WC()->cart->cart_contents_total;	
       if($amount<100)
		{
		   WC()->session->set('chosen_shipping_methods', array( 'flat_rate:1' ) );
		}
		if($amount>=100)
		{
		   WC()->session->set('chosen_shipping_methods', array( 'shiptoschool' ) );
		}
	  
	
?>
<!--<script type="text/javascript">
	 
		jQuery( function($){
		  $('.actions input[name=update_cart]').on('click', function () {
				//	$('body').trigger('wc_fragment_refresh');
					$('body').trigger('updated_cart_totals');
					console.log("clickkkkkk");
					setTimeout(function() {
                   //   $('body').trigger('wc_fragment_refresh');
				   	$('body').trigger('updated_cart_totals');
                    }, 2500);
					
		 });

		});
    </script>-->
<?php	 
}
// Cart Page Shipping - Radio button
//add_action( 'woocommerce_cart_contents', 'woo_onchange_shipping_method' );
add_action( 'woocommerce_after_shipping_rate', 'woo_onchange_shipping_method' );
function woo_onchange_shipping_method()
{

      $amount = WC()->cart->cart_contents_total;		
	
	  if ( WC()->session->get('installment_fee') == 1 ) {
		$check=1;
	   }
	   else if ( WC()->session->get('installment_fee') == 0 ){
		   $check=0;
	   }
	   else
	   {
		  $check=0; 
	   }

    ?>

    <script type="text/javascript">
         jQuery('.cart-collaterals').on('change','input[name^="shipping_method"]',function() {
            var val = jQuery( this ).val();
			console.log(val);
			var check = "<?php echo $check; ?>";
			var amt = "<?php echo $amount; ?>";
            if (val) {
				if(amt>=100 && check==1 && val=="flat_rate:1")
				{
					var selection = "shiptoschool";
					//alert(selection);
					jQuery("input[name^='shipping_method'][value='shiptoschool']").attr('checked', true);
				}
				else if(amt<100 && check==0 && val=="shiptoschool")
				{
					var selection = "flat_rate:1";
					//alert(selection);
					jQuery("input[name^='shipping_method'][value='flat_rate:1']").attr('checked', true);
				}
				else{
					 location.reload();
				}
              //   location.reload();
			     
               }
         });
    </script>

<?php	
}

add_filter( 'woocommerce_form_field' , 'remove_optional_txt_from_installment_checkbox', 10, 4 );
function remove_optional_txt_from_installment_checkbox( $field, $key, $args, $value ) {
    // Only on checkout page for Order notes field
    if( 'installment_fee' === $key && is_checkout() ) {
        $optional = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
        $field = str_replace( $optional, '', $field );
    }
    return $field;
}

// Register new status
function register_wait_call_order_status() {
	register_post_status( 'wc-waiting-call', array(
	'label'                     => 'Waiting call',
	'public'                    => true,
	'show_in_admin_status_list' => true,
	'show_in_admin_all_list'    => true,
	'exclude_from_search'       => false,
	'label_count'               => _n_noop( 'Waiting call (%s)', 'Waiting call (%s)' )
	) );
	register_post_status( 'wc-processing-call', array(
	'label'                     => 'Processing call',
	'public'                    => true,
	'show_in_admin_status_list' => true,
	'show_in_admin_all_list'    => true,
	'exclude_from_search'       => false,
	'label_count'               => _n_noop( 'Processing call (%s)', 'Processing call (%s)' )
	) );
}
// Add custom status to order status list
function add_wait_call_to_order_statuses( $order_statuses ) {
	$new_order_statuses = array();
	foreach ( $order_statuses as $key => $status ) {
	$new_order_statuses[ $key ] = $status;
	if ( 'wc-on-hold' === $key ) {
	$new_order_statuses['wc-waiting-call'] = 'Waiting call';
	$new_order_statuses['wc-processing-call'] = 'Processing call';
	}

	}
	return $new_order_statuses;
}
add_action( 'init', 'register_wait_call_order_status' );
add_filter( 'wc_order_statuses', 'add_wait_call_to_order_statuses' );

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');
function custom_checkout_field_update_order_meta($order_id){
    if (!empty($_POST['installment_fee'])) {
		//$order_type=get_post_meta($order_id, 'wc_order_type', true);
		$user = wp_get_current_user();
		
	    if(!is_user_logged_in() || in_array('seller', (array) $user->roles))
		{
			update_post_meta($order_id, 'ship_with_catalog',$_POST['installment_fee']);
			update_post_meta($order_id, 'wc_order_type', "ship-to-school");
		}
    }
}

add_action('woocommerce_payment_complete', 'func_custom_thankyou_change_order_status', 10, 1);
function func_custom_thankyou_change_order_status($order_id) {
    if (!$order_id)
        return;
    $order = wc_get_order($order_id);
    if (get_post_meta($order_id, 'ship_with_catalog', true) == 1){
        $order->update_status('wc-waiting-call');
    } else {
        
    }
}
/*
add_action( 'woocommerce_thankyou', 'wc_change_status' );
function wc_change_status( $order_id ) {
 if (!$order_id)
        return;
    $order = wc_get_order($order_id);
    if (get_post_meta($order_id, 'ship_with_catalog', true) == 1){
        $order->update_status('wc-waiting-call');
    } else {
        
    } 
}
*/
// Main Order Merge Ajax
add_action('wp_ajax_nopriv_wpm_order_merge_ajax', 'wpm_order_merge_ajax'); // for not logged in users
add_action('wp_ajax_wpm_order_merge_ajax', 'wpm_order_merge_ajax');

function wpm_order_merge_ajax()
{	
	$IDT=$_POST['fid'];
	
	$mID=get_user_meta($IDT, 'wc_ship_mergeOrderID', true );
	$mQanty=get_user_meta($IDT, 'wc_ship_mergeOrderQuanty', true );
	
	if(empty($mID) && empty($mQanty))
	{
		$order_quantity_test=0;
		$args = array(
						'status' => array('wc-waiting-call'),
						'meta_key' => 'fundraiser_field',
						'meta_value' => $IDT,
						'limit' => -1
				);
		$orders = wc_get_orders($args);
		$totalOrdersQ=count($orders);
		if(!empty($totalOrdersQ))
		{
			$merge_productlist=array();
			$prID=0;
			$ship_orders_ID=array();
			$total_order=array();
			$cal_seller_arr=array();
			foreach ( $orders as $forder ) 
			{			
				$order = wc_get_order( $forder->ID );
				//$order = new WC_Order($forder->ID);
				$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
				$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
				$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							
				if($order_type=="ship-to-school")
				{
					if ($scat==1) { 
								 
										//Active/Inactive 
										$order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
										$extract_date = $order_date;
										$inactive_date = get_user_meta($frID3, "user_close_out_date_inactive", true);
										$inactive_s = get_user_meta($frID3, "user_action_status", true);
									
										$in_s=1;
										
										if ($inactive_date < $extract_date && $inactive_s==2)
										{
											$in_s=2;
										}
										else if($inactive_s==1 || empty($inactive_s))
										{
											$in_s=1;
										}
										else{
											$in_s=1;
										}
									
										if ($in_s==1)
										{		
												$slrID  = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$cal_seller=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
												}
												else if(is_numeric($slrID))
												{
													 $slr_info  = get_userdata($slrID);
													 $cal_seller = $slr_info->first_name." ".$slr_info->last_name; 
												}
												else
												{}
												if(!in_array($cal_seller,$cal_seller_arr))
												{
													$cal_seller_arr[]=$cal_seller;
												}
												foreach ($order->get_items() as $item_key => $item_values): 
													$item_data = $item_values->get_data(); 
													$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
													if($order_type=="ship-to-school") 
													{
														$order_quantity_test += $item_data['quantity']; 
														
														$prID = $item_data['product_id'];
														$prName = $item_data['name'];
														$quanty = $item_data['quantity'];
														$ShipOrderID=$order->get_id();
														$total_order[]=$order->get_id();
														$merge_productlist[] = array('ID' => $prID,'p_name' => $prName,'p_qty' => $quanty,'o_id'=>$order->get_id());
														if(!in_array($ShipOrderID,$ship_orders_ID))
														{
															$ship_orders_ID[]=$ShipOrderID;
															
														}
														
														
													}
													
											endforeach; 
											
										}
							} 
							
						}
				}
				

				// Create New Merge Order 
				
				if(!empty($cal_seller_arr))
				{
					$cal_total_seller=count($cal_seller_arr);
					$seller_name_line = implode(",",$cal_seller_arr);
				}
				$fname =  get_user_meta( $IDT, 'first_name', true );
				$lname = get_user_meta( $IDT, 'last_name', true );
				$company  = get_user_meta( $IDT, 'user_registration_name_of_school_business', true );
				$user_info = get_userdata($IDT);
				$email = $user_info->user_email;
				$address_1 = get_user_meta( $IDT, 'user_registration_street_address', true );
				$address_2  = "";
				$city = get_user_meta( $IDT, 'user_registration_City2', true );
				$phone  = get_user_meta( $IDT, 'user_registration_input_box_phone', true );
				$postcode  = get_user_meta( $IDT, 'user_registration_input_box_zip', true );
				$country  =  get_user_meta( $IDT, 'billing_country', true );
				$state  =  get_user_meta( $IDT, 'user_registration_select_state', true );

				$countries = new WC_Countries(); // Get an instance of the WC_Countries Object
				$countries_array = $countries->get_countries();
				$country_name = $countries_array[$country];
				
				$country_states = $countries->get_states( $country ); // Get the states array from a country code

				foreach( $country_states as $key=>$wc_states ) 
				{
					if($state==$wc_states)
					{
						$scode_state=$key;
					}
				}
				if(empty($fname))
				{
					$fname="";
				}
				if(empty($lname))
				{
					$lname="";
				}
				if(empty($company))
				{
					$company="";
				}
				if(empty($email))
				{
					$email="";
				}
				if(empty($address_1))
				{
					$address_1="";
				}
				if(empty($city))
				{
					$city="";
				}
				if(empty($postcode))
				{
					$postcode="";
				}
				if(empty($country_name))
				{
					$country_name="";
				}
				if(empty($state))
				{
					$state="";
				}
				if(empty($scode_state))
				{
					$scode_state="";
				}
						
				$address = array(
				'first_name' => $fname,
				'last_name'  => $lname,
				'company'    => $company,
				'email'      => $email,
				'phone'      => $phone,
				'address_1'  => $address_1,
				'address_2'  => '', 
				'city'       => $city,
				'state'      => $scode_state,
				'postcode'   => $postcode,
				'country'    => $country
				);
				
			  
				// create Fee object
				$fee = new WC_Order_Item_Fee();
				$fee->set_name( 'Processing fee' );
				$total_no_of_orders=count($ship_orders_ID);
				$p_fee = $total_no_of_orders*1.95;
				$fee->set_amount( $p_fee );
				$fee->set_total( $p_fee );
				
				// add to order
				$norder = wc_create_order();
				
				// Custom Meta Saving
				$morder_type = "ship-to-school";
				$ship_merge_orders_id=implode(',',$ship_orders_ID);
				update_post_meta($norder->ID, 'wc_order_type', $morder_type );
				update_post_meta($norder->ID, 'fundraiser_field', $IDT );
				update_post_meta($norder->ID, 'ship_with_catalog', 1);
				update_post_meta($norder->ID, 'ship_orders_ids', $ship_merge_orders_id );
				update_post_meta($norder->ID, 'ship_orders_total_items', $order_quantity_test );
				update_post_meta($norder->ID,'wc_seller_total_mergeOrder',$cal_total_seller);
				update_post_meta($norder->ID,'wc_seller_name_mergeOrder',$seller_name_line);
				
				$norder->set_address( $address, 'billing' );
				$norder->set_address( $address, 'shipping' );
				
				$norder->add_item( $fee );
				
				for($i=0;$i<count($merge_productlist);$i++)
				{
					$norder->add_product( wc_get_product( $merge_productlist[$i]['ID'] ), $merge_productlist[$i]['p_qty'] );
				}
				$note = "Fund #ID: ".$IDT;
				//$norder->add_order_note( $note );
				$norder->set_customer_note($note);
				
				$payment_gateways = WC()->payment_gateways->payment_gateways();
				$norder->set_payment_method( $payment_gateways[ 'authorize' ] );
				
				$item = new WC_Order_Item_Shipping();

				$item->set_method_title( "Free Shipping" );
				$item->set_method_id( "shiptoschool" );
				$norder->add_item( $item );
				
				$norder->set_status( 'wc-processing-call' );
				$norder->calculate_totals();
				$norder->save();
				
				$merge_orderID = $norder->ID;
				if(!empty($merge_orderID))
				{
					update_user_meta($IDT, 'wc_ship_mergeOrderID', $merge_orderID );
					update_user_meta($IDT, 'wc_ship_mergeOrderQuanty', $order_quantity_test );
				}
				else
				{
					
				}
		    
				
	}
	
	wp_send_json_success(array(
				'action' => "Your Ship to school orders are placed successfully."));
}
else
{
	wp_send_json_success(array(
				'action' => "Something went wrong!!"));
}	
}

// Discount ship to school - Popup Ajax
add_action('wp_ajax_nopriv_wdm_discount_shiptoschool', 'wdm_discount_shiptoschool'); // for not logged in users
add_action('wp_ajax_wdm_discount_shiptoschool', 'wdm_discount_shiptoschool');

function wdm_discount_shiptoschool()
{
	$order_quantity_test=0;
	$IDT=$_POST['user_id'];
	$args = array(
                 'status' => array('wc-waiting-call'),
                 'meta_key' => 'fundraiser_field',
				 'meta_value' => $IDT,
				 'limit' => -1
           );
    $orders = wc_get_orders($args);
	foreach ( $orders as $forder ) 
	{			
			$order = wc_get_order( $forder->ID );
			$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
			$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
			$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
			
			if($order_type=="ship-to-school")
			{
				if ($scat==1) { 
			         
							//Active/Inactive 
							$order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
							$extract_date = $order_date;
							$inactive_date = get_user_meta($frID3, "user_close_out_date_inactive", true);
							$inactive_s = get_user_meta($frID3, "user_action_status", true);
						
							$in_s=1;
							
							if ($inactive_date < $extract_date && $inactive_s==2)
							{
								$in_s=2;
							}
							else if($inactive_s==1 || empty($inactive_s))
							{
								$in_s=1;
							}
							else{
								$in_s=1;
							}
						
							if ($in_s==1)
							{		
								   foreach ($order->get_items() as $item_key => $item_values): 
                                        $item_data = $item_values->get_data(); 
										$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
										if($order_type=="ship-to-school") 
										{
											$order_quantity_test += $item_data['quantity']; 
											
										}
										
								endforeach; 
								
							}
				} 
				
			}
	}
	
	wp_send_json_success(array(
				'action' => $order_quantity_test));
}

add_filter('woocommerce_checkout_fields', 'check_shipping_method_on_checkout');

function check_shipping_method_on_checkout($fields) {

    // CRITICAL: this filter also runs during the WooCommerce update_order_review
    // AJAX. Emitting ANY markup below during that AJAX corrupts the returned JSON
    // fragments, so the "Your Order" review block stays stuck under the loading
    // spinner and shipping / Place Order can never be used. Only output on a normal
    // page render, never during AJAX.
    if ( wp_doing_ajax() || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
        return $fields;
    }

    //  $amount = WC()->cart->cart_contents_total;

	  // Guard: WC session is null in Customizer / REST / admin contexts.
	  if ( ! WC()->session ) {
		  $check = 0;
	  } elseif ( WC()->session->get('installment_fee') == 1 ) {
		$check=1;
	   }
	   else if ( WC()->session->get('installment_fee') == 0 ){
		   $check=0;
	   }
	   else
	   {
		  $check=0; 
	   }

    ?>

    <script type="text/javascript">
         jQuery('form.checkout').on('change','input[name^="shipping_method"]',function() {
            var val = jQuery( this ).val();
			console.log(val);
			var check = "<?php echo $check; ?>";
            if (val) {
				if(check==1 && val=="flat_rate:1")
				{
					var selection = "shiptoschool";
					//alert(selection);
					jQuery("input[name^='shipping_method'][value='shiptoschool']").attr('checked', true);
				}
				else if(check==0 && val=="shiptoschool")
				{
					var selection = "flat_rate:1";
					//alert(selection);
					jQuery("input[name^='shipping_method'][value='flat_rate:1']").attr('checked', true);
				}
				else{
					 location.reload();
				}
              //   location.reload();
			    jQuery('body').trigger('update_checkout');
               }
         });
    </script>

    <?php
    return $fields;
}

/*Admin Panel Css*/
add_action('admin_head', 'custom_changes_css');

function custom_changes_css() {
?>
<style>
	.post-type-shop_order .wp-list-table th
	{
		width: 10ch !important;
	}
	.components-surface.components-card .css-10klw3m
	{
		    height: auto;
	}
	.notice.notice-error.jquery-migrate-dashboard-notice
	{
		display:none;
	}
</style>  
<?php	
}


/*
 * goes in theme functions.php or a custom plugin
 *
 *   woocommerce_email_heading_new_order
 *   woocommerce_email_heading_customer_processing_order
 *   woocommerce_email_heading_customer_completed_order
 *   woocommerce_email_heading_customer_invoice
 *   woocommerce_email_heading_customer_note
 *   woocommerce_email_heading_low_stock
 *   woocommerce_email_heading_no_stock
 *   woocommerce_email_heading_backorder
 *   woocommerce_email_heading_customer_new_account
 *   woocommerce_email_heading_customer_invoice_paid
 *
 * USAGE: add_filter('woocommerce_email_heading_new_order', 'my_filter_email_heading_new_order', 10, 2);
 *
 **/

/*Admin Email Heading */
function my_filter_email_heading_new_order($email_heading, $email){

    /* get info if needed */
    $order_id = $email->get_id();

    /* simple version */
    //  $email_heading = 'Hello world';
	
	$order_type = get_post_meta($order_id, 'wc_order_type', true );
	
	if($order_type=="Online"){
		 $email_heading = sprintf( __('Glow Scented Candles - Online Order', 'royal'));
	}
	else if($order_type=="Catlog")
	{
		 $email_heading = sprintf( __('Glow Scented Candles - Brochure Order', 'royal'));
	}
	else if($order_type=="ship-to-school") 
	{
		 $email_heading = sprintf( __('Glow Scented Candles - Ship to School/Group Order', 'royal') );
	}
	else
	{
		 $email_heading = sprintf( __('Glow Scented Candles - New Order', 'royal'));
	}
    return $email_heading;
}
add_filter('woocommerce_email_heading_new_order', 'my_filter_email_heading_new_order', 10, 2);

/*Customer Email Heading */
function my_filter_email_heading_customer_processing_order($email_heading, $email){

    /* get info if needed */
    $order_id = $email->get_id();

    /* simple version */
    //  $email_heading = 'Hello world';
	
	$order_type = get_post_meta($order_id, 'wc_order_type', true );
	
	if($order_type=="Online"){
		 $email_heading = sprintf( __('Glow Scented Candles - Online Order', 'royal'));
	}
	else if($order_type=="Catlog")
	{
		 $email_heading = sprintf( __('Glow Scented Candles - Brochure Order', 'royal'));
	}
	else if($order_type=="ship-to-school") 
	{
		 $email_heading = sprintf( __('Glow Scented Candles - Ship to School/Group Order', 'royal'));
	}
	else
	{
		 $email_heading = sprintf( __('Glow Scented Candles - New Order', 'royal'));
	}
    return $email_heading;
}
add_filter('woocommerce_email_heading_customer_processing_order', 'my_filter_email_heading_customer_processing_order', 10, 2);

/* Admin Email Subject */

/*
 * goes in theme functions.php or a custom plugin
 *
 * Subject filters: 
 *   woocommerce_email_subject_new_order
 *   woocommerce_email_subject_customer_processing_order
 *   woocommerce_email_subject_customer_completed_order
 *   woocommerce_email_subject_customer_invoice
 *   woocommerce_email_subject_customer_note
 *   woocommerce_email_subject_low_stock
 *   woocommerce_email_subject_no_stock
 *   woocommerce_email_subject_backorder
 *   woocommerce_email_subject_customer_new_account
 *   woocommerce_email_subject_customer_invoice_paid
 **/
add_filter('woocommerce_email_subject_new_order', 'change_admin_email_subject', 1, 2);

function change_admin_email_subject( $subject, $order ) {
	
	global $woocommerce;
		
	$order_type = get_post_meta($order->id, 'wc_order_type', true );
	
	if($order_type=="Online"){
		$subject = sprintf( 'Glow Scented Candles - Online Order (# %s)', $order->id );
	}
	else if($order_type=="Catlog")
	{
		$subject = sprintf( 'Glow Scented Candles - Brochure Order (# %s)', $order->id);
	}
	else if($order_type=="ship-to-school") 
	{
		 $subject = sprintf( 'Glow Scented Candles - Ship to School/Group Order (# %s)', $order->id);
	}
	else
	{
		$subject = sprintf( 'Glow Scented Candles - New Order (# %s)', $order->id);
	}		
		
	return $subject;
}

add_filter('woocommerce_email_subject_customer_processing_order', 'change_customer_email_subject', 1, 2);

function change_customer_email_subject( $subject, $order ) {
	
	global $woocommerce;
		
	$order_type = get_post_meta($order->id, 'wc_order_type', true );
	
	if($order_type=="Online"){
		$subject = sprintf( 'Glow Scented Candles - Online Order (# %s)', $order->id );
	}
	else if($order_type=="Catlog")
	{
		$subject = sprintf( 'Glow Scented Candles - Brochure Order (# %s)', $order->id);
	}
	else if($order_type=="ship-to-school") 
	{
		 $subject = sprintf( 'Glow Scented Candles - Ship to School/Group Order (# %s)', $order->id);
	}
	else
	{
		$subject = sprintf( 'Glow Scented Candles - New Order (# %s)', $order->id);
	}		
		
	return $subject;
}

add_action('woocommerce_order_status_processing', 'woo_processing_call_order');
add_action('woocommerce_order_status_on-hold', 'woo_processing_call_order');
function woo_processing_call_order( $order_id ) {

	if (is_user_logged_in()) {
		
	$user = wp_get_current_user();
	$user_info = get_userdata($user->ID);
	
	if(in_array('fundraiser', (array) $user_info->roles ))
	{
			/* Ship to School */
			$order_type = get_post_meta( $order_id, 'wc_order_type', true);
			$first_catalog_order=get_user_meta( $user->ID, 'wc_first_catlog_order', true);
			$mID=get_user_meta($user->ID, 'wc_ship_mergeOrderID', true );
			$mQanty=get_user_meta($user->ID, 'wc_ship_mergeOrderQuanty', true );
	
		/* MERGE ORDER AJAX */
		   if($order_type=='Catlog')
		   { 
				if(empty($first_catalog_order) || $first_catalog_order=="")
				{
					if(empty($mID) && empty($mQanty))
					{
					$order_quantity_test=0;
					$IDT=$user->ID;
					$args = array(
									 'status' => array('wc-waiting-call'),
									 'meta_key' => 'fundraiser_field',
									 'meta_value' => $IDT,
									 'limit' => -1
							   );
					$orders = wc_get_orders($args);
					$totalOrdersQ=count($orders);
					if(!empty($totalOrdersQ))
					{
						$merge_productlist=array();
						$prID=0;
						$ship_orders_ID=array();
						$total_order=array();
						$cal_seller_arr=array();
						foreach ( $orders as $forder ) 
						{			
								$order = wc_get_order( $forder->ID );
								//$order = new WC_Order($forder->ID);
								$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								
								if($order_type=="ship-to-school")
								{
									if ($scat==1) { 
										 
												//Active/Inactive 
												$order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
												$extract_date = $order_date;
												$inactive_date = get_user_meta($frID3, "user_close_out_date_inactive", true);
												$inactive_s = get_user_meta($frID3, "user_action_status", true);
											
												$in_s=1;
												
												if ($inactive_date < $extract_date && $inactive_s==2)
												{
													$in_s=2;
												}
												else if($inactive_s==1 || empty($inactive_s))
												{
													$in_s=1;
												}
												else{
													$in_s=1;
												}
											
												if ($in_s==1)
												{		
														$slrID  = get_post_meta($order->get_id(), 'wc_seller_field', true );
														if($slrID=="custom")
														{
															$cal_seller=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
														}
														else if(is_numeric($slrID))
														{
															 $slr_info  = get_userdata($slrID);
															 $cal_seller = $slr_info->first_name." ".$slr_info->last_name; 
														}
														else
														{}
														if(!in_array($cal_seller,$cal_seller_arr))
														{
															$cal_seller_arr[]=$cal_seller;
														}
														foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data(); 
															$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
															if($order_type=="ship-to-school") 
															{
																$order_quantity_test += $item_data['quantity']; 
																
																$prID = $item_data['product_id'];
																$prName = $item_data['name'];
																$quanty = $item_data['quantity'];
																$ShipOrderID=$order->get_id();
																$total_order[]=$order->get_id();
																$merge_productlist[] = array('ID' => $prID,'p_name' => $prName,'p_qty' => $quanty,'o_id'=>$order->get_id());
																if(!in_array($ShipOrderID,$ship_orders_ID))
																{
																	$ship_orders_ID[]=$ShipOrderID;
																	
																}
																
																
															}
															
													endforeach; 
													
												}
									} 
									
								}
						}
						

						// Create New Merge Order 
						
						if(!empty($cal_seller_arr))
						{
							$cal_total_seller=count($cal_seller_arr);
							$seller_name_line = implode(",",$cal_seller_arr);
						}
						$fname =  get_user_meta( $IDT, 'first_name', true );
						$lname = get_user_meta( $IDT, 'last_name', true );
						$company  = get_user_meta( $IDT, 'user_registration_name_of_school_business', true );
						$user_info = get_userdata($IDT);
						$email = $user_info->user_email;
						$address_1 = get_user_meta( $IDT, 'user_registration_street_address', true );
						$address_2  = "";
						$city = get_user_meta( $IDT, 'user_registration_City2', true );
						$phone  = get_user_meta( $IDT, 'user_registration_input_box_phone', true );
						$postcode  = get_user_meta( $IDT, 'user_registration_input_box_zip', true );
						$country  =  get_user_meta( $IDT, 'billing_country', true );
						$state  =  get_user_meta( $IDT, 'user_registration_select_state', true );

						$countries = new WC_Countries(); // Get an instance of the WC_Countries Object
						$countries_array = $countries->get_countries();
						$country_name = $countries_array[$country];
						
						$country_states = $countries->get_states( $country ); // Get the states array from a country code

						foreach( $country_states as $key=>$wc_states ) 
						{
							if($state==$wc_states)
							{
								$scode_state=$key;
							}
						}
						if(empty($fname))
						{
							$fname="";
						}
						if(empty($lname))
						{
							$lname="";
						}
						if(empty($company))
						{
							$company="";
						}
						if(empty($email))
						{
							$email="";
						}
						if(empty($address_1))
						{
							$address_1="";
						}
						if(empty($city))
						{
							$city="";
						}
						if(empty($postcode))
						{
							$postcode="";
						}
						if(empty($country_name))
						{
							$country_name="";
						}
						if(empty($state))
						{
							$state="";
						}
						if(empty($scode_state))
						{
							$scode_state="";
						}
								
						$address = array(
						'first_name' => $fname,
						'last_name'  => $lname,
						'company'    => $company,
						'email'      => $email,
						'phone'      => $phone,
						'address_1'  => $address_1,
						'address_2'  => '', 
						'city'       => $city,
						'state'      => $scode_state,
						'postcode'   => $postcode,
						'country'    => $country
						);
						
					  
						// create Fee object
						$fee = new WC_Order_Item_Fee();
						$fee->set_name( 'Processing fee' );
						$total_no_of_orders=count($ship_orders_ID);
						$p_fee = $total_no_of_orders*1.95;
						$fee->set_amount( $p_fee );
						$fee->set_total( $p_fee );
						
						// add to order
						$norder = wc_create_order();
						
						// Custom Meta Saving
						$morder_type = "ship-to-school";
						$ship_merge_orders_id=implode(',',$ship_orders_ID);
						update_post_meta($norder->ID, 'wc_order_type', $morder_type );
						update_post_meta($norder->ID, 'fundraiser_field', $IDT );
						update_post_meta($norder->ID, 'ship_with_catalog', 1);
						update_post_meta($norder->ID, 'ship_orders_ids', $ship_merge_orders_id );
						update_post_meta($norder->ID, 'ship_orders_total_items', $order_quantity_test );
						update_post_meta($norder->ID,'wc_seller_total_mergeOrder',$cal_total_seller);
						update_post_meta($norder->ID,'wc_seller_name_mergeOrder',$seller_name_line);
						
						$norder->set_address( $address, 'billing' );
						$norder->set_address( $address, 'shipping' );
						
						$norder->add_item( $fee );
						
						for($i=0;$i<count($merge_productlist);$i++)
						{
							$norder->add_product( wc_get_product( $merge_productlist[$i]['ID'] ), $merge_productlist[$i]['p_qty'] );
						}
						$note = "Fund #ID: ".$IDT;
						//$norder->add_order_note( $note );
						$norder->set_customer_note($note);
						
						$payment_gateways = WC()->payment_gateways->payment_gateways();
						$norder->set_payment_method( $payment_gateways[ 'authorize' ] );
						
						$item = new WC_Order_Item_Shipping();

						$item->set_method_title( "Free Shipping" );
						$item->set_method_id( "shiptoschool" );
						$norder->add_item( $item );
						
						$norder->set_status( 'wc-processing-call' );
						$norder->calculate_totals();
						$norder->save();
						
						$merge_orderID = $norder->ID;
						if(!empty($merge_orderID))
						{
							update_user_meta($IDT, 'wc_ship_mergeOrderID', $merge_orderID );
							update_user_meta($IDT, 'wc_ship_mergeOrderQuanty', $order_quantity_test );
						}
						else
						{
							
						}
						
						
					}
					/* USER META Updates */
						update_user_meta( $user->ID, 'wc_first_catlog_order', "Yes");
						update_user_meta( $user->ID, 'wc_first_catlog_orderID', $order_id);
							
						/* ORDER POST META */
						update_post_meta( $order_id, 'wc_first_catlog_order', "Yes"); 
					
				}
				}
				else if($first_catalog_order=="Yes")
				{
					
				}
				else{
					
				}
		   }
	   }
	
	}

}

function cst_addColumn( $columns ) {
    $order_actions  = $columns['order-actions']; // Save Order actions
    unset($columns['order-actions']); // Remove Order actions

    // Add your custom column key / label
    $columns['custom-column'] = __( 'Tracking No.', 'woocommerce' );

    // Add back previously saved "Order actions"
    $columns['order-actions'] = $order_actions;

    return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'cst_addColumn', 10, 1 );

// Display data in custom column to the "My Orders" table in the account.
function cst_display_data( $order ) {
    $order_id = $order->get_id();
	//echo $order_id;
	global $wpdb; 

    $table_perfixed = $wpdb->prefix . 'comments';
    $results = $wpdb->get_results("
        select comment_content from wp_comments where comment_post_id=$order_id 
		AND  `comment_content` LIKE  '%tracking number%';
        
    ");
	foreach( $results as $result ){
		$data_track = $result->comment_content;
		$tack_number = explode(" ",$data_track);
		$name = "number"; //name to be searched
		$foundIndex = array_search($name, $tack_number);
		$remove_data =  $tack_number[$foundIndex+1];
		$final_value = substr($remove_data, 0, -1);
		echo $final_value;
	}
	
	
}
add_action( 'woocommerce_my_account_my_orders_column_custom-column', 'cst_display_data' );



// date 29/02/2024

function lf_custom_query_vars( $vars ) {
    $vars['view-my-order'] = 'view-my-order';

    return $vars;
}
add_filter( 'woocommerce_get_query_vars', 'lf_custom_query_vars', 0 );

// Flushes rewrite rules (required with custom WC pages?)
function lf_custom_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action( 'wp_loaded', 'lf_custom_flush_rewrite_rules' );

// Replaces native "View" button button next to each order on the list
add_filter('woocommerce_my_account_my_orders_actions', function($actions, $order) {
    $user = wp_get_current_user();
    $allowed_roles = ['fundraiser'];

    if ( array_intersect( $allowed_roles, $user->roles ) ) {
        $actions['view'] = [
            'url' => wc_get_endpoint_url( 'view-order', $order->get_id() ),
            'name' => __( 'View', 'txtdomain' )
        ];
    }

    return $actions;
}, 10, 2); 

// Renders new template
function lf_view_my_order_endpoint_content() { 
    $order_id = get_query_var('view-my-order');

    wc_get_template('myaccount/view-my-order.php', [
        'order_id' => $order_id,
    ]);
}
add_action( 'woocommerce_account_view-my-order_endpoint', 'lf_view_my_order_endpoint_content' );
///sachin code 02/03/2024
add_shortcode( 'bartag', 'cst_email_send' );
function cst_email_send() {
	
	
	
	$order_id = $_REQUEST['order_id'];
	//print_r($order_id);
	//exit();
	
	if(!empty($order_id)){
			
		update_post_meta( $order_id, 'claim_requested', 1 );
		
		
		
		$claim_product_arrays = $_REQUEST['claim_product_array'];
		$fundraise_id  = $_REQUEST['fundraise_id'];
		$seller_name_1 = $_REQUEST['seller_name'];
		$broken_image_id = $_REQUEST['broken_image_id'];
		//echo $broken_image_id;
		$broken_image_url = wp_get_attachment_url( $broken_image_id );
		//echo $broken_image_url;
		//exit();
		//echo $seller_name_1;
		//echo $order_id;
		
		$order_details = new WC_Order( $order_id );

		$payment_title = $order_details->get_payment_method_title();
		$tran_id = $order_details->get_transaction_id();
		$order_date = $order_details->get_date_created();
		$ship_method = $order_details->get_shipping_method();
		$shipping_first_name = $order_details->get_shipping_first_name();
		$shipping_last_name  = $order_details->get_shipping_last_name();
		$shipping_company    = $order_details->get_shipping_company();
		$shipping_address_1  = $order_details->get_shipping_address_1();
		$shipping_address_2  = $order_details->get_shipping_address_2();
		$shipping_city       = $order_details->get_shipping_city();
		$shipping_state      = $order_details->get_shipping_state();
		$shipping_postcode   = $order_details->get_shipping_postcode();
		$shipping_country    = $order_details->get_shipping_country();
		$shipping_phone    	 = $order_details->get_billing_phone();
		$shipping_date    	 = $order_details->get_date_completed();
		$total_payment    	 = $order_details->get_total();
		
		$Shipping_name 	= $shipping_first_name . ' ' . $shipping_last_name;
		$Shipping_address = $shipping_address_1 . ' ' .  $shipping_address_2 . ' ' . $shipping_city . ' ' . $shipping_postcode . ' ' . $shipping_country;
		$f_order_date = (new DateTime($order_date))->format('Y-m-d');
		$s_order_date = (new DateTime($shipping_date))->format('Y-m-d');
		
		$ord_detais = array(
		
					'Order ID'        	=>	$order_id,
					'Fundraiser ID'   	=>	$fundraise_id,
					'Order Date'		=>	$f_order_date,
					'Shipping Date'		=>	$s_order_date,
					'Shipping Method'	=>	$ship_method,
					'Shipping Name'		=>	$Shipping_name,
					'Shipping Address'	=>	$Shipping_address,
					'Customer Phone'	=>	$shipping_phone,
					'Payment Method'	=>	$payment_title,
					'Transaction ID'	=>	$tran_id,
					'Total Payment'		=>	$total_payment
				);
		$message1='<u><h3>ORDER DETAILS</h3></u>';
		$message1.='<table border="2" style="width:80%;">';
		foreach ($ord_detais as $x => $y) {
			$message1.='<tr><th align= "left" style="padding-left:10px;">'.$x.'</th><td style="padding-left:10px;">'.$y.'</td></tr>';
		}
		$message1.='</table>';
		
		
		$message = $message1;
		$message .=  '<hr width=80% align="left">';
		$message .=  '<u><h3>PRODUCT CLAIM DETAILS</h3></u>';
		$message .= '<table border="2" style="width:80%;" ><tr><th>Product ID</th><th>Product name</th><th>Quantity</th><th>Seller name</th><th>Region</th></tr>';
		$claim_strings = array();
		foreach( $claim_product_arrays as $claim_product_array ) {
			$claim_strings[] = explode('_', $claim_product_array);
				
		}
		//print_r($claim_strings );
		foreach( $claim_strings as $claim_string ) { 
			$qty = $claim_string[0];
			$id = $claim_string[1];
			
			//print_r($id);
			//exit();
			$seller_name = $claim_string[2];
			if($qty==0){
				$qty=1;			
			}
			if( empty( $seller_name ) ) {
				$seller_name = $seller_name_1;
			}
			$region = $claim_string[3];
			if($region == 'm' ) {
				$final_r = 'Missing';
			}
			elseif($region == 'b' ) {
				$final_r = 'Broken';
			}
			$product_name = $claim_string[4];
			//$product = wc_get_product( $id );
			//$product_name = strip_tags($product->get_name());
			$message .= '<tr><td>'. $id .'</td><td>'. $product_name .'</td><td>'. $qty .'</td><td>'.$seller_name.'</td><td>'. $final_r .'</td></tr>';
				
		}
		$message .= '</table><br> <h3><u>Claim Image</u></h3>';
		$message .= '<img src="'. $broken_image_url .'" height=500px width=500px>';
		
		//exit();
		//$claim_string= implode(',', $claim_product_array);
		//echo $claim_string
		//exit();
		
		
		$to = 'candace@glowscentedcandles.com';
		//$to = 'sachink701@gmail.com';
		

		// Subject line for the email.
		$subject = 'Order Reconciliation order id =' . $order_id;

		// The body of the email.
		$message = $message;

		// Headers for the email.
		$headers = array('Content-Type: text/html; charset=UTF-8');

		// Send the email.
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			// The email has been sent successfully.
			echo 'Email sent successfully';
		} else {
			// The email failed to send.
			echo 'Email failed to send';
		}  

global $current_user;
wp_get_current_user();

$user_id = $current_user->ID;

if ( ! $user_id ) {
    return 'User not logged in';
}

$product_id = 59085;   // your product ID
$quantity   = 1;

// Create order
$order = wc_create_order( array(
        'customer_id' => $user_id
    ));

    // Add product
$product = wc_get_product( $product_id );
$order->add_product( $product, $quantity );

$billing_data = array(
    'first_name' => get_user_meta( $user_id, 'billing_first_name', true ),
    'last_name'  => get_user_meta( $user_id, 'billing_last_name', true ),
    'email'      => get_user_meta( $user_id, 'billing_email', true ),
    'phone'      => get_user_meta( $user_id, 'billing_phone', true ),
    'address_1'  => get_user_meta( $user_id, 'billing_address_1', true ),
    'city'       => get_user_meta( $user_id, 'billing_city', true ),
    'state'      => get_user_meta( $user_id, 'billing_state', true ),
    'postcode'   => get_user_meta( $user_id, 'billing_postcode', true ),
    'country'    => 'USA'
);



 foreach ( $billing_data as $key => $value ) {
        $order->{"set_billing_$key"}($value);
    }

    // Shipping info if needed
    $order->set_address( $billing_data, 'shipping' );

    // Set payment method (any valid method)
    $order->set_payment_method('cod'); // COD or stripe etc.

    // Calculate totals
    $order->calculate_totals();

    // FORCE mark payment complete for $0 order
    $order->payment_complete();
       // 🔥 Force Completed first
    $order->update_status( 'completed', 'Programmatically completed first.' );

    // 🔥 Then move back to Processing (for ShipStation)
    $order->update_status( 'processing', 'Moved back to processing for ShipStation.' );

	$order_id = $order->get_id();
	update_post_meta( $order_id, 'fundraiser_field', $user_id );
	update_post_meta( $order_id, 'claim_requested', 1 );

// ---- OPTIONAL: FORCE MANUAL EMAIL SEND ---- //

    $emails = WC()->mailer()->get_emails();
if ( ! empty( $emails['WC_Email_New_Order'] ) ) {
    $emails['WC_Email_New_Order']->trigger( $order_id );
}


	}
	exit();
}

add_action('wp_ajax_cst_email_send','cst_email_send');
add_action('wp_ajax_nopriv_cst_email_send', 'check_claim_status');




function handle_image_upload() {
    

    // Check if the files were uploaded
    if ( empty( $_FILES['broken_image'] ) ) {
        wp_send_json_error( 'No files uploaded.' );
    }

    $file = $_FILES['broken_image'];

    // Handle the WordPress media upload
    $upload = wp_handle_upload( $file, array( 'test_form' => false ) );
    
    // Check if upload succeeded
    if ( isset( $upload['error'] ) ) {
        wp_send_json_error( $upload['error'] );
    }

    // Prepare an array of post data for the attachment
    $attachment_data = array(
        'guid'           => $upload['url'], 
        'post_mime_type' => $upload['type'],
        'post_title'     => sanitize_file_name( basename( $file['name'] ) ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Insert the attachment
    $attachment_id = wp_insert_attachment( $attachment_data, $upload['file'] );
    
    // Generate the metadata for the attachment, and update the database record
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
	wp_update_attachment_metadata( $attachment_id, $attach_data );

    echo $attachment_id;
	exit();
}

// Register the AJAX action for authenticated users
add_action( 'wp_ajax_upload_image', 'handle_image_upload' );

//28-4-24

function cst_allowed( $allowed, $order_ids ) {
	$cst_user_ID = get_current_user_id();
	echo 'User Id : '.$cst_user_ID;
	
	$user = new WP_User( $cst_user_ID );

	if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
		foreach ( $user->roles as $role )
			$user_role = $role;
	} 
	if($user_role == 'fundraiser' ){
		$allowed = true;
	}
	return $allowed;
}

add_filter( 'wpo_wcpdf_check_privs', 'cst_allowed', 10, 2 );

//06-04-2024

function eye_icon_function() {
    ?>
	<script defer>
		jQuery(document).ready(function () {
			//jQuery('.show-password-input').addClass('new-class');
			jQuery('#user_pass').after('<span id="toggle-password2" class="show-password-input fa fa-eye"></span>'); 
			jQuery("#toggle-password2").click(function () {
    jQuery(this).toggleClass("fa-eye fa-eye-slash");
    let input = jQuery(jQuery(this).prev("input"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
	  // To change the color of all paragraphs to blue using jQuery
jQuery('#toggle-password2').css('top', '11px');

    } else {
      input.attr("type", "password");
	 jQuery('#toggle-password2').css('top', '2px');

    }
  });
		});
	</script>
													<style>
#user_pass{
  position: relative;
}

#toggle-password2 {
  position: absolute;
  cursor: pointer;
  /* Position it to the right side of the input field */
  right: 10px;
  top: 2px; /* Adjust the top value as needed */
}

</style>

	<?php
}
add_action( 'wp_footer', 'eye_icon_function' );

function display_products_ajax_handler() {
	$category = isset($_POST['category']) ? $_POST['category'] : 'classic-candles';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

	$offset = ($page - 1) * 10;

    // Get products based on category
    $product_args = array(
        'post_status'   => 'publish',
        'post_type'   	=> 'product',
        'posts_per_page'  => 10,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => $category
			),
		),
        'order'         => 'ASC',
		'offset'        => $offset
    );
	$products_query = new WP_Query($product_args);

    ob_start();

	if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            // Output product HTML
            ?>
            <div class="product-item">
				<?php
				$postid = get_the_ID();
				$product = wc_get_product($postid);
				$price = $product->get_regular_price();
				$item_no = $product->get_sku();
				$image = wp_get_attachment_image_src(get_post_thumbnail_id($postid), 'single-post-thumbnail');
				?>
				<div class="product-image">
					<?php if (!empty($image)) { ?>
						<img src="<?php echo $image[0]; ?>" class="card-image" />
					<?php } else { ?>
						<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/09/news-20190130-202923.png" class="card-image" />
					<?php } ?>
				</div>
				<div class="product-details">
					<span class="item_no"><?php echo $item_no; ?></span> <?php echo $product->get_name(); ?><br>
					<span class="price"><?php echo $price; ?></span><br>
					<div class="quantity">
						<input type="button" value="-" class="minus">
						<input type="text" name="quantity" value="1" class="input-text qty text" step="1" min="0" max="9999">
						<input type="button" value="+" class="plus">
					</div>
					<a href="<?= $product->add_to_cart_url() ?>" class="add-to-cart" data-product="<?php echo $postid; ?>" data-price="<?php echo $price; ?>">Add to Cart</a>
				</div>
			</div>
            <?php
        }
    } else {
        echo 'No products found.';
    }

	wp_send_json_success(array(
		'html' => ob_get_clean(),
		'current_page' => $page,
		'total_pages' => $products_query->max_num_pages
	));

    wp_die();
}
add_action('wp_ajax_display_products', 'display_products_ajax_handler');
add_action('wp_ajax_nopriv_display_products', 'display_products_ajax_handler');
add_action( 'init', 'pre_populate_fundraiser_id' );
function pre_populate_fundraiser_id() {
    // Early initialize customer session
    if ( isset(WC()->session) && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }

    if ( isset( $_GET["frid"] ) && isset( $_GET["seller_name"] ) ) {
         $frid = $_GET["frid"];
		 $session_name = $_GET["seller_name"];
        // Set the session data
        WC()->session->set( 'fridID', $frid );
		WC()->session->set( 'session_name', $session_name );
    }
	/*Top Bar code moved */

	
}


function display_products($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'category' => 'classic-candles', // Default category
    ), $atts);

    ob_start(); // Start output buffering

	?>
		<style>
			.load-more-product-container {
				display: grid;
				grid-template-columns: 2fr 2fr 2fr 2fr 2fr;
				padding-top: 80px;
				padding-bottom: 80px;
				text-align: center;
				gap: 40px 30px;
			}

			img.card-image {
				height: 180px;
				object-fit: contain;
				width: 100%;
			}

			.load-more-btn {
				font-size: 16px;
				font-family: "Proxima Nova";
				max-width: 170px;
				height: 45px;
				margin: 0 auto;
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.load-more-product-container .product-details {
				font-size: 14px;
				font-weight: 600;
			}

			.load-more-product-container .item_no {
				padding-right: 6px;
				color: #000;
			}

			.load-more-product-container .quantity {
				height: 26px;
				width: 204px;
				display: flex;
				border: 1px solid #000;
			}

			.load-more-product-container .quantity input[type="button"],
			.load-more-product-container .quantity input.qty {
				height: 24px !important;
			}

			.load-more-product-container .quantity input[type="button"],
			.load-more-product-container .quantity input.qty {
				border: 0px !important;
			}

			.load-more-product-container .quantity input[type="button"] {
				position: unset;
				background: #e3e3e3 !important;
			}

			.load-more-product-container .add-to-cart {
				width: 100%;
				background: #86c5a1;
				color: #000;
				height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                
			}

			.load-more-product-container .quantity input.qty {
				padding: 5px 10px;
				text-align: center;
				font-size: 16px;
				width: 74%;
			}

			.product-item {
				background: #fff;
			}
		</style>
		<div class="load-more-product-wrapper <?= $atts['category'] ?>-products-wrapper"> 
			<div class="product-container load-more-product-container"></div>
			<button id="load-more" data-category="<?= $atts['category'] ?>" data-page="1" data-total-pages="" class="load-more-btn" style="display:none;">Load More</button>
		</div>
    <?php

    // Return buffered output
    return ob_get_clean();
}
add_shortcode('display_products', 'display_products');

add_action('wp_footer', function() {
?>
<script>
	jQuery(document).ready(function($) {
		$('.load-more-btn').on('click', function() {
			const $btn = $(this);
			const category = $btn.data('category');
			const page = $btn.data('page');
			
			$.ajax({
				url: '<?= admin_url('admin-ajax.php') ?>',
				type: 'POST',
				data: {
					action: 'display_products',
					category: category,
					page: page,
				},
				success: function(response) {
					$btn.prev().append(response.data.html);
					if (page === response.data.total_pages) {
						$btn.hide();
					} else {
						const nextPage = page + 1;
						$btn.data('page', nextPage)
						$btn.show();
					}
				},
				error: function(xhr, status, error) {
					console.error(status + ': ' + error);
				}
			});
		});

		$('.load-more-btn').each(function() {
			$(this).trigger('click')
		})

	});
</script>
<?php
});

add_action('wp_ajax_nopriv_representative_paynow', 'funct_representative_paynow'); // for not logged in users
add_action('wp_ajax_representative_paynow', 'funct_representative_paynow');
function funct_representative_paynow( )
{
	$refID = $_POST['referralID'];
	$paid = $_POST['paid'];
	if($paid==1)
	{
		//Post Meta
		$current_payment_postid=get_user_meta($refID,"um_unique_payment_id",true);
		date_default_timezone_set('America/Chicago');
		$current_date = date('Y-m-d H:i:s a');
		update_post_meta($current_payment_postid,'payment_date',$current_date);	
		update_post_meta($current_payment_postid,'paid_status',$paid);
		update_post_meta($current_payment_postid,'reps_id',$refID);
		update_user_meta($refID,'payment_date',$current_date);	
		
		// Total Amoount/commission 
		$userdata = get_userdata( $refID );
        $ref_email=$userdata->user_email;
		$q=0;
		$exist_fids_arr=array();
		$fundraiser_ids=get_post_meta($current_payment_postid,"fundraiser_ids",true);
		if(!empty($fundraiser_ids))
		{
			$exist_fids_arr=explode(",",$fundraiser_ids);
			$q=1;
		}
	
		if($q==1)
		{
		$fundraisers_list = get_users(array( 
		'role' => array( 'fundraiser' ),
		'include' => $exist_fids_arr,
		'meta_key'     => 'user_registration_referral_email',
		'meta_value'   => $ref_email,
		'meta_compare' => '='
		));	
		
		$get_referral_commission_amount_full = 0;
		$totalAmount_full=0;
		$w=0;
		$v=0;
		$new_inactive_fund_ids=array();
		$new_active_fund_ids=array();
		$new_inactive_fund_ids_sTr="";
		$new_active_fund_ids_sTr="";
		foreach($fundraisers_list as $fundraisers) {
				$ID = $fundraisers->data->ID;
	            $action_status= get_user_meta($ID,'user_action_status',true );
				if(empty($action_status) || $action_status==1)
				{
					$new_active_fund_ids[$v]=$ID;
					$v++;
				}
				if($action_status==2)
				{
					$new_inactive_fund_ids[$w]=$ID;
					$org = get_user_meta($ID,'user_registration_name_of_school_business',true );
					if(empty($org))
					{
						$org=get_user_meta($ID,'user_registration_input_company',true );
					}
	            	$email = $fundraisers->data->user_email;
	            	$firstname =  get_user_meta($fundraisers->data->ID,'first_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);

	            	$get_referral = get_user_meta($ID,'user_registration_referral_email',true);
			        $referralUser = get_user_by( 'email', $get_referral );        
					$referralUserID = $referralUser->data->ID;


			        $get_referral_commission_amount = 0;
					$totalAmount = 0;
					$total_sale_so_far_Catlog = 0;
					$total_sale_so_far = 0;


			        if(get_user_meta($referralUserID,'reps_commission',true)){
			         $get_referral_commission = get_user_meta($referralUserID,'reps_commission',true);       
			        }else{
			        $get_referral_commission = 0;
			        }

	            	$args = array(
					'status'   => "published",
					'meta_key' => 'fundraiser_field',
					'meta_value' => $ID
					);
					
					$orders = wc_get_orders($args);
					foreach ( $orders as $order ){
						
						$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
						$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
						$order_data = $order->get_data();
						$order_customer_id = $order_data['customer_id'];
						if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) 
						{
							/* Active/Inactive */	
							$order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
							$extract_date=$order_date;
							$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
							$inactive_s=get_user_meta($frID3, "user_action_status", true);
							$in_s=1;
							if ($inactive_date < $extract_date && $inactive_s==2){
								$in_s=2;
							}
							else if($inactive_s==1 || empty($inactive_s)){
								$in_s=1;
							}
							else{
								$in_s=1;
							}
							/*$inactive_date="2020-08-29 01:00:01";*/
							$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							if ($in_s ==1){
								if ($frID3 != $order_customer_id){
									if($order_type=="Online"){
										$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount)){
											$newamt=$order->get_subtotal()-$tdiscount;
											$total_sale_so_far+=$newamt;
										}else{
											$total_sale_so_far+=$order->get_subtotal();
										}
									}
								}
								else if ($frID3 == $order_customer_id){
									if($order_type=="Catlog"){
										$subtot=$order->get_subtotal(); 
										$total_sale_so_far_Catlog+=$subtot; 
									}
								}
								else{
								
								}
							}
							else {
								if ($frID3 == $order_customer_id){
									if($order_type=="Catlog"){
										$subtot=$order->get_subtotal(); 
										$total_sale_so_far_Catlog+=$subtot; 
									}
								}
								else{
								
								}
							}
						}
					
					}
					/* Calculate Ship to school Total Sales */
						$total_sale_so_far2=0;
						  $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order && method_exists($order, "get_date_created") && $order->get_date_created()) ? $order->get_date_created()->date("Y-m-d H:i:s") : "";
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
									$in_s=1;
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id){ 
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if ($order_type=='ship-to-school') {
								
										$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount))
										{
										  $newamt2=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt2;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                    } 
								} 
							} 
						}
					}
					$totalAmount = $total_sale_so_far_Catlog + $total_sale_so_far + $total_sale_so_far2;
					$totalAmount_full+=$totalAmount;
					$get_referral_commission_amount = floatval ($totalAmount) * floatval ($get_referral_commission) / 100;
					$get_referral_commission_amount_full+=$get_referral_commission_amount;
				
					}
					$w++;
				}
				if(!empty($new_inactive_fund_ids))
				{
					$new_inactive_fund_ids_sTr = implode(",",$new_inactive_fund_ids);
				}
				if(!empty($new_active_fund_ids))
				{
					$new_active_fund_ids_sTr = implode(",",$new_active_fund_ids);
				}
				update_post_meta($current_payment_postid,'after_payment_fundraiser_ids_inactive',$new_inactive_fund_ids_sTr);	
				update_post_meta($current_payment_postid,'after_payment_fundraiser_ids_still_active',$new_active_fund_ids_sTr);	
				update_post_meta($current_payment_postid,'total_commission',$get_referral_commission_amount_full);	
				update_post_meta($current_payment_postid,'total_amount',$totalAmount_full);	
				update_post_meta($current_payment_postid,'referral_commission',$referral_commission);	
				
				$current_paymentPostID=get_user_meta( $refID, "um_unique_payment_id", true );
				$exist_payids=get_user_meta( $refID, "um_unique_payment_id_arr", true );
				if(!empty($exist_payids))
				{
					$exist_payids_arr=explode(",",$exist_payids);
					if (!in_array($current_paymentPostID,$exist_payids_arr)) {
						array_push($exist_payids_arr, $current_paymentPostID);
						$exist_payids_arrNew=implode(",",$exist_payids_arr);
						update_user_meta($refID,'um_unique_payment_id_arr',$exist_payids_arrNew);
						
					 } 
				}
				else if(empty($exist_payids)){
					update_user_meta($refID,'um_unique_payment_id_arr',$current_paymentPostID);
				}
				else{
					
				}
				
				$args=array('post_title'=>'Payment #'.$refID, 'post_type'=>'payment_history', 'post_status' => 'publish', 'post_author' => $refID);
				$post_id = wp_insert_post($args, true);
				if(!empty($post_id))
				{
					update_post_meta($post_id, "payment_id", $post_id );
					update_post_meta($post_id,'fundraiser_ids',$new_active_fund_ids_sTr);
				//	update_post_meta($post_id,'after_payment_fundraiser_ids_still_active',$new_active_fund_ids_sTr);	
					update_user_meta( $refID, "um_unique_payment_id", $post_id );
				}
			}
		
		}	
		wp_send_json_success(array(
		'paid' => $paid
		));
}

add_action('wp_ajax_nopriv_ajax_seller_register_email_validation', 'ajax_seller_register_email_validation'); // for not logged in users
add_action('wp_ajax_ajax_seller_register_email_validation', 'ajax_seller_register_email_validation');
function ajax_seller_register_email_validation( )
{
	$email = $_POST['seller_email'];
	$user_id = email_exists( $email );
	if ( $user_id ) {
		$res=2;
		$user_info = get_userdata($user_id);
		if(in_array( 'fundraiser', (array) $user_info->roles ))
		{
			$user_type=1;
		}
		else if(in_array( 'seller', (array) $user_info->roles ))
		{
			$user_type=2;
		}
		else{
			$user_type=0;
		}
		wp_send_json_success(array(
				'action' => $res,'user_type' => $user_type));
	} else {
		$res=1;
		wp_send_json_success(array(
				'action' => $res));
	}
}
function glowscented_users_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for subscribers
		 if (in_array('fundraiser', $user->roles)) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to =  ("" . glow_dynamic_url_base('home') . "fundraiser-dashboard/");
        }
		if (in_array('seller', $user->roles)) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to =  ("" . glow_dynamic_url_base('home') . "seller-dashboard/");
        }
        if (in_array('representative', $user->roles)) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to =  ("" . glow_dynamic_url_base('home') . "representative-dashboard/");
        }
    }

    return $redirect_to;
}

add_filter( 'login_redirect', 'glowscented_users_login_redirect', 10, 3 );

add_filter( 'woocommerce_login_redirect', 'woousers_glow_customer_login_redirect', 9999, 2 );
 
function woousers_glow_customer_login_redirect( $redirect, $user ) {
     
    if ( wc_user_has_role( $user, 'fundraiser' ) ) {
        $redirect = "/fundraiser-dashboard";
        
    }
   if ( wc_user_has_role( $user, 'seller' ) ) {
        $redirect = "/seller-dashboard";
    
    }
	 if ( wc_user_has_role( $user, 'representative' ) ) {
        $redirect = "/representative-dashboard";
       
    }
    return $redirect;
}
/*Session ReStart for seller profile */
add_action('wp_ajax_nopriv_ajax_seller_restart_session', 'ajax_seller_restart_session'); // for not logged in users
add_action('wp_ajax_ajax_seller_restart_session', 'ajax_seller_restart_session');
function ajax_seller_restart_session( )
{
	if ( isset(WC()->session) && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }
	$query=$_POST['username'];
	$user = get_user_by('login', $query);
	$u_id = $user->ID;
	$fid=get_user_meta( $u_id , 'user_registration_seller_fundraiser' , true );
	$f=get_user_meta( $u_id , 'first_name' , true );		
	$l=get_user_meta( $u_id , 'last_name' , true );	
	$sname=$f." ".$l;
	WC()->session->set( 'fridID', $fid );
	WC()->session->set( 'session_name', $sname );
}
/*Session ReStart for fundraiser profile */
add_action('wp_ajax_nopriv_ajax_fund_restart_session', 'ajax_fund_restart_session'); // for not logged in users
add_action('wp_ajax_ajax_fund_restart_session', 'ajax_fund_restart_session');
function ajax_fund_restart_session( )
{
	if ( isset(WC()->session) && ! WC()->session->has_session() ) {
        WC()->session->set_customer_session_cookie( true );
    }
	$query=$_POST['userid'];
	WC()->session->set( 'fridID', $query );
}
/*
add_filter( 'woocommerce_shortcode_products_query', 'exclude_out_of_stock_from_shortcodes', 10, 3 );
function exclude_out_of_stock_from_shortcodes( $query_args, $atts, $loop_name ) {
    if ( in_array( $loop_name, array( 'sale_products', 'products' ) ) ) {
        $query_args['meta_query'][] = array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '='
        );
    }
    return $query_args;
}
*/
function yearly_best_sellers_shortcode( $atts ) {
    global $wpdb;

    // Attributes (default = 6)
    $atts = shortcode_atts(
        array(
            'limit' => 6,
        ),
        $atts,
        'yearly_best_sellers'
    );

    // Date range: last 1 year
    $date = date( 'Y-m-d H:i:s', strtotime( '-1 year' ) );

    // Query product IDs sold in last 1 year
    $results = $wpdb->get_col( $wpdb->prepare("
        SELECT order_item_meta.meta_value AS product_id
        FROM {$wpdb->prefix}woocommerce_order_items AS order_items
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta
            ON order_items.order_item_id = order_item_meta.order_item_id
        INNER JOIN {$wpdb->prefix}posts AS posts
            ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
          AND posts.post_status IN ('wc-completed','wc-processing')
          AND order_item_meta.meta_key = '_product_id'
          AND posts.post_date >= %s
        GROUP BY product_id
        ORDER BY SUM( order_items.order_item_id ) DESC
        LIMIT %d
    ", $date, intval( $atts['limit'] ) ) );

    if ( ! empty( $results ) ) {
        return do_shortcode( '[products limit="6" columns="3" ids="' . implode( ',', $results ) . '"]' );
    } else {
        return '<p>No products sold in the last year.</p>';
    }
}
add_shortcode( 'yearly_best_sellers', 'yearly_best_sellers_shortcode' );


add_shortcode('fund', function ($atts = []) {
    $a = shortcode_atts([
        'key' => '',
        'id'  => '',   // optional; if omitted, use current user
    ], $atts, 'fund');

    if (empty($a['key'])) return '';

    // Use current user if no ID provided
    $user_id = $a['id'] ? intval($a['id']) : get_current_user_id();
    if (!$user_id) return '';

    // Load WP_User object once
    $user = get_user_by('id', $user_id);
    if (!$user) return '';

    // Helper to fetch & escape user meta
    $umeta = function($meta_key) use ($user_id) {
        $val = get_user_meta($user_id, $meta_key, true);
        return is_scalar($val) ? esc_html($val) : '';
    };

    $key = strtolower(trim($a['key']));
    switch ($key) {
        // === Mapped fields (friendly keys -> your actual meta keys) ===
        case 'organization_name':
			  return $umeta('user_registration_name_of_school_business');

        case 'check_payable_to':
            return $umeta('user_registration_name_of_school_business');

			
        case 'fundraiser_id':
        case 'fund_id':
            return (string) $user_id;

        case 'fundraising_reason':
            return $umeta('user_registration_reason_for_fundraising');

        case 'items_goal':
            return $umeta('user_registration_selling_goal');

        case 'end_date':
            return $umeta('user_registration_end_date');

        case 'chairperson_name':
            $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            if (!$name) $name = $user->display_name ?? '';
            return esc_html($name);

        case 'chairperson_contact':
            $phone = get_user_meta($user_id, 'user_registration_input_box_phone', true);
            $email = $user->user_email ?? '';
            $out   = trim($phone) ? esc_html($phone) : '';
            if ($email) {
                $out .= ($out ? ' / ' : '') . esc_html($email);
            }
            return $out;

        // === Convenience: seller register URL with frid ===
        case 'register_url':
            $url = add_query_arg('frid', $user_id, ('' . glow_dynamic_url_base('home') . 'seller-register/'));
            return esc_url($url);

        // === QR image: outputs an <img> tag if file exists ===
        case 'qr_code':
            $path = WP_CONTENT_DIR . "/uploads/qrcodes/image_seller_register{$user_id}.png";
            $url  = content_url("uploads/qrcodes/image_seller_register{$user_id}.png");
            if (file_exists($path)) {
                // allow only the img tag/attrs
                $img = '<img src="' . esc_url($url) . '" alt="QR Code" width="100" height="100" style="max-width:100%;height:auto;" />';
                return wp_kses($img, ['img' => ['src'=>[], 'alt'=>[], 'width'=>[], 'height'=>[], 'style'=>[]]]);
            }
            return '';

        // === Fallback: allow direct access to any meta key ===
        default:
            return $umeta($key); // e.g., [fund key="user_registration_end_date"]
    }
});


add_filter( 'wpcf7_load_css', '__return_false' );


// WooCommerce already sends the customer on-hold notification on status transitions.
// Do not register a second trigger on woocommerce_order_status_on-hold.

function send_on_hold_email_to_customer( $order_id, $order = false ) {
    if ( ! $order ) {
        $order = wc_get_order( $order_id );
    }

    if ( ! $order ) {
        return;
    }

    // Get WooCommerce mailer instance
    $mailer = WC()->mailer();

    // Get the specific "Customer On Hold Order" email object
    $email = $mailer->emails['WC_Email_Customer_On_Hold_Order'];

    if ( $email ) {
        // Send the email
        $email->trigger( $order_id );
    }
}

/**
 * Create $0 WooCommerce Order when Fundraiser form submits
 */
add_action( 'user_registration_after_register_user_action', 'glow_create_zero_order_after_registration', 10, 3 );

function glow_create_zero_order_after_registration( $valid_form_data, $form_id, $user_id ) {

    // Only run for Fundraiser registration form IDs
    if ( ! in_array( (int) $form_id, array( 62179, 37978, 21998, 62316 ), true ) ) return;
    $product_id = 58612;   // your product ID
    $quantity   = 1;
    //$user_id    = get_current_user_id(); // or manually set a user ID

    if ( empty( $user_id ) ) {
        return;
    }

    // Bail SAFELY if the product is missing on this environment. Previously a
    // missing product made $order->add_product( false, ... ) fatal; because this
    // runs on the post-registration hook, that fatal turned the registration AJAX
    // response into an HTML error page, so User Registration's JS threw
    // "Unexpected token '<' ... is not valid JSON" on submit.
    $product = wc_get_product( $product_id );
    if ( ! $product instanceof WC_Product ) {
        error_log( 'glow_create_zero_order_after_registration: product ' . $product_id . ' not found; skipping zero-order for user ' . $user_id );
        return;
    }

    // Create order
    $order = wc_create_order( array(
        'customer_id' => $user_id
    ));
    if ( is_wp_error( $order ) || ! $order instanceof WC_Order ) {
        error_log( 'glow_create_zero_order_after_registration: wc_create_order failed for user ' . $user_id );
        return;
    }

    // Add product
    $order->add_product( $product, $quantity );

    // Read a UR field value safely (missing keys must not warn/fatal here).
    $glow_fd = function( $key ) use ( $valid_form_data ) {
        return ( isset( $valid_form_data[ $key ] ) && isset( $valid_form_data[ $key ]->value ) )
            ? $valid_form_data[ $key ]->value
            : '';
    };

    // Set billing info (required)
    $billing_data = array(
        'first_name' => $glow_fd( 'first_name' ),
        'last_name'  => $glow_fd( 'last_name' ),
        'email'      => $glow_fd( 'user_email' ),
        'phone'      => $glow_fd( 'input_box_1595312762' ),
        'address_1'  => $glow_fd( 'billing_address_1' ),
        'city'       => $glow_fd( 'billing_city' ),
        'state'      => $glow_fd( 'billing_state' ),
        'postcode'   => $glow_fd( 'billing_postcode' ),
        'country'    => 'USA'
    );

    foreach ( $billing_data as $key => $value ) {
        $order->{"set_billing_$key"}($value);
    }

    // Shipping info if needed
    $order->set_address( $billing_data, 'shipping' );

    // Set payment method (any valid method)
    $order->set_payment_method('cod'); // COD or stripe etc.

    // Calculate totals
    $order->calculate_totals();

    // FORCE mark payment complete for $0 order
    $order->payment_complete();
       // 🔥 Force Completed first
    $order->update_status( 'completed', 'Programmatically completed first.' );

    // 🔥 Then move back to Processing (for ShipStation)
    $order->update_status( 'processing', 'Moved back to processing for ShipStation.' );
	$order_id = $order->get_id();
	update_post_meta( $order_id, 'fundraiser_field', $user_id );
}


// add_filter( 'cron_schedules', 'add_daily_cron_interval' );
// function add_daily_cron_interval( $schedules ) {
//     $schedules['daily_custom'] = array(
//         'interval' => 86400, // 24 hours in seconds
//         'display'  => 'Once Daily',
//     );
//     return $schedules;
// }

// if ( ! wp_next_scheduled( 'daily_fundraiser_notification_hook' ) ) {
//     wp_schedule_event( time(), 'daily_custom', 'daily_fundraiser_notification_hook' );
// }

// // 3. The Email Logic
// add_action( 'daily_fundraiser_notification_hook', 'send_daily_fundraiser_notification' );

// function send_daily_fundraiser_notification() {
//     // If today is 2026-01-10, this targets 2026-01-09
//     $yesterday = date( 'Y-m-d', strtotime( '-1 day' ) );


//     $args = array(
//         'meta_query' => array(
//             'relation' => 'AND',
//             array(
//                 'key'     => 'user_registration_fundraiser_preference',
//                 'value'   => 'Virtual Fundraiser - No Brochures.', 
//                 'compare' => 'LIKE' // LIKE is safer for hidden spaces
//             ),
//             array(
//                 'key'     => 'user_registration_end_date',
//                 'value'   => $yesterday,
//                 'compare' => 'LIKE',
//                 'type'    => 'DATE'
//             ),
//             // Ensure we don't send the same email every 5 minutes
//             array(
//                 'key'     => '_fundraiser_email_sent_date',
//                 'value'   => $yesterday,
//                 'compare' => 'NOT EXISTS' 
//             )
//         )
//     );

//     $users = get_users( $args );
 
//     if ( ! empty( $users ) ) {
//         // Headers for 2026 deliverability
//         $headers = array('Content-Type: text/html; charset=UTF-8','From: ' . GLOW_FROM_EMAIL);

//         foreach ( $users as $user ) {
     	// $ID = $user->ID;
		// date_default_timezone_set('America/Chicago');	 
		// $current_date = date('Y-m-d H:i:s a');
		// $args=array('post_title'=>'Close Out #'.$ID, 'post_type'=>'close_out', 'post_status' => 'publish', 'post_author' => $ID);
	    // $post_id = wp_insert_post($args,  true);
	
		// if(!empty($post_id))
	    // {
			
		// 	update_post_meta( $post_id , 'close out_fundraiser_id', $ID);
		//     update_post_meta( $post_id , 'close out_fundraiser_date', $current_date); 
		// 	update_user_meta($ID, "user_close_out_value", 1);
		// 	update_user_meta($ID, "user_close_out_date_inactive", $current_date);
		// 	update_user_meta($ID, 'user_action_status' , 2 );
		// }

//             $to      = 'hamzatechypros105@gmail.com'; 
//             $subject = 'Virtual Fundraiser Update';
//             $message = "Hello " . $user->display_name . ",<br><br>Your fundraiser ended on " . $yesterday . ". We are following up as you selected 'Virtual Fundraiser - No Brochures.'";
            
//             $mail_sent = wp_mail( $to, $subject, $message, $headers );

//             if ( $mail_sent ) {
//                 // Tag user so they are NOT found in the next 5-minute run
//                 update_user_meta( $user->ID, '_fundraiser_email_sent_date', $yesterday );
//             }
//         }
//     }
// }

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('fundraiser-ajax', get_stylesheet_directory_uri() . '/js/fundraiser-ajax.js', ['jquery'], null, true);

    // Pass ajax URL to JS
    wp_localize_script('fundraiser-ajax', 'fundraiser_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});
// // Save fundraiser data in session via AJAX
add_action('wp_ajax_save_fundraiser_session', 'save_fundraiser_session');
add_action('wp_ajax_nopriv_save_fundraiser_session', 'save_fundraiser_session');

function save_fundraiser_session() {
	if ( ! function_exists('WC') || ! WC()->session ) {
        return;
    }
    if (isset($_POST['popup_fundraiser_id']) && isset($_POST['popup_fundraiser_name'])) {
       
        WC()->session->set('fridID', $_POST['popup_fundraiser_id']);
       
          WC()->session->set('seller_name', $_POST['popup_fundraiser_name']);
        

        wp_send_json_success([
            'message' => 'Fundraiser info saved in session!',
            'profile_link' => $_POST['profile_link']
        ]);
    } else {
        wp_send_json_error([
            'message' => 'Missing fields.'
        ]);
    }

    wp_die();
}
function show_php_limits() {
    return '
    <h3>PHP Info</h3>
    <ul>
        <li>PHP Memory Limit: ' . ini_get('memory_limit') . '</li>
        <li>Max Execution Time: ' . ini_get('max_execution_time') . '</li>
        <li>Upload Max Filesize: ' . ini_get('upload_max_filesize') . '</li>
        <li>Post Max Size: ' . ini_get('post_max_size') . '</li>
    </ul>';
}
add_shortcode('php_limits', 'show_php_limits');

/**
 * --------------------------------------------------------
 * WooCommerce 3% Credit/Debit Card Processing Fee
 * --------------------------------------------------------
 */

/**
 * Add 3% fee for card-based payments
 */
add_action('woocommerce_cart_calculate_fees', 'woo_add_card_processing_fee');

function woo_add_card_processing_fee($cart) {

    if (is_admin() && !defined('DOING_AJAX')) return;
    if (!WC()->session) return;

    $payment_method = WC()->session->get('chosen_payment_method');

    if (empty($payment_method)) return;

    // Detect card-based gateways (Stripe, WooPayments, etc.)
    $card_keywords = array(
        'stripe',
        'card',
        'credit',
        'visa',
        'mastercard',
        'amex',
        'discover',
        'woocommerce_payments',
        'wc_stripe'
    );

    $is_card_payment = false;

    foreach ($card_keywords as $keyword) {
        if (strpos($payment_method, $keyword) !== false) {
            $is_card_payment = true;
            break;
        }
    }

    if ($is_card_payment) {

        // Calculate 3% fee from subtotal
        $fee = $cart->get_subtotal() * 0.03;

        if ($fee > 0) {
            $cart->add_fee(
                __('Credit/Debit Card Processing Fee (3%)', 'woocommerce'),
                $fee,
                true
            );
        }
    }
}


/**
 * Force checkout refresh when payment method changes
 */
add_action('wp_footer', 'woo_refresh_checkout_on_payment_change');

function woo_refresh_checkout_on_payment_change() {

    if (!is_checkout()) return;
    ?>
    <script type="text/javascript">
        jQuery(function($){
            $('form.checkout').on('change', 'input[name="payment_method"]', function(){
                $('body').trigger('update_checkout');
            });
        });
    </script>
    <?php
}
