<?php

global $etheme_theme_data;
$etheme_theme_data = wp_get_theme( get_stylesheet_directory() . '/style.css' );
require_once( get_template_directory() . '/framework/init.php' );
require_once( get_template_directory() . '/shortcode/shortcode.php' );
require_once( get_template_directory() . '/shortcode/shortcode-new.php' );
require_once( get_template_directory() . '/admin-panel/fundraiser_operation.php' );
require_once( get_template_directory() . '/admin-panel/post-types.php' );
require_once( get_template_directory() . '/rebooking/rebooking-post-ajax.php' );

require_once( get_template_directory() . '/admin-panel/send_check_orders.php' );
require_once( get_template_directory() . '/admin-panel/search_fundrasier_ajax.php' );
require_once( get_template_directory() . '/email/fundraiser_email_automation.php' );
require_once( get_template_directory() . '/admin-panel/fundraiser_email_tracking.php' );
require_once( get_template_directory() . '/admin-panel/fundrasier-booking-detail.php' );
require_once( get_template_directory() . '/admin-panel/theme_fundraiser_feedbacks.php' );
require_once( get_template_directory() . '/admin-panel/wp_outstanding_fund_ajax.php' );
require_once( get_template_directory() . '/admin-panel/fundraiser-outstanding.php' );

require_once( get_template_directory() . '/admin-panel/ajax_send_check_received_email.php' );
/////
//custome code starts here
wp_enqueue_style( 'style', get_template_directory_uri() . '/css/custom_css.css');

// proxima-nova font
wp_enqueue_style( 'proxima-nova-font', get_template_directory_uri() . '/fonts/proxima-nova/fonts.css');

// Localize the script with new data
wp_register_script( 'ajax-js', get_template_directory_uri() . '/js/custom_js.js', array( 'jquery' ), 1.1, true );
wp_localize_script( 'ajax-js', 'ajax_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'site_url' => site_url(), 'theme_url' => get_template_directory_uri() ) );
wp_enqueue_script( 'ajax-js' );

/*
Post type: Feedback
*/
function wp_custom_post_feedback() {

//labels array added inside the function and precedes args array

$labels = array(
'name' => _x( 'Feedback', 'feedback_name' ),
'singular_name' => _x( 'Feedback', 'feedback_singular' ),
'add_new' => _x( 'Add New', 'Feedback' ),
'add_new_item' => __( 'Add New Feedback' ),
'edit_item' => __( 'Edit Feedback' ),
'new_item' => __( 'New Feedback' ),
'all_items' => __( 'All Feedback' ),
'view_item' => __( 'View Feedback' ),
'search_items' => __( 'Search Feedback' ),
'not_found' => __( 'No Feedback found' ),
'not_found_in_trash' => __( 'No Feedback found in the Trash' ),
'parent_item_colon' => '',
'menu_name' => 'Feedback'
);

// args array

$args = array(
'labels' => $labels,
'description' => 'Displays Feedback',
'public' => true,
'menu_position' => 4,
'supports' => array( 'title', 'editor', 'thumbnail' ),
'has_archive' => true,
);
register_post_type( 'feedback', $args );
}
add_action( 'init', 'wp_custom_post_feedback' );

/*
* Creating a function to create our CPT
*/
	function custom_post_type() {
	// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Fundraisers', 'Fundraisers', 'twentythirteen' ),
			'singular_name'       => _x( 'Fundraiser', 'Fundraiser', 'twentythirteen' ),
			'menu_name'           => __( 'fundraiser', 'twentythirteen' ),
			'all_items'           => __( 'All Fundraisers', 'twentythirteen' ),
			'view_item'           => __( 'View fundraiser', 'twentythirteen' ),
			'add_new_item'        => __( 'Add New Fundraisers', 'twentythirteen' ),
			'add_new'             => __( 'Add New', 'twentythirteen' ),
			'edit_item'           => __( 'Edit Fundraiser', 'twentythirteen' ),
			'update_item'         => __( 'Update Fundraiser', 'twentythirteen' ),
			'search_items'        => __( 'Search Fundraiser', 'twentythirteen' ),
			'not_found'           => __( 'Not Found', 'twentythirteen' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
		);

	// Set other options for Custom Post Type

		$args = array(
			'label'               => __( 'fundraiser', 'twentythirteen' ),
			'description'         => __( 'fundraisers', 'twentythirteen' ),
			'labels'              => $labels,
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
			// You can associate this CPT with a taxonomy or custom taxonomy.
			'taxonomies'          => array( 'genres' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);

		// Registering your Custom Post Type
		register_post_type( 'fundraiser', $args );

	}

	/* Hook into the 'init' action so that the function
	* Containing our post type registration is not
	* unnecessarily executed.
	*/

	add_action( 'init', 'custom_post_type', 0 );



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
		session_start();
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
		session_start();

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
		session_start();

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
		/*$headers = "From: Glow Scented Candles <info@glowglowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: Glow Scented Candles <info@glowglowscentedcandles.com');
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
		$ID = $_POST['fund_id'];
		$user_info = get_userdata($ID);
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
			   $org=get_user_meta( $u_id , 'user_registration_input_company' , true );
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
                        
                             
                                 if ($item_data['quantity']>99){
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
                        ?>
		 <tr>
             <td align="left" style="padding: 30px 30px;">
			 <div class="text-left" style="float:left;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Glow Scented Candles:</strong><br>
							4628 Industrial Street<br>
							Rowlett, TX 75088<br>
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
			$total_sale_fundraiser= number_format($total_sale_so_far, 2, '.', ''); 
			$total_rebate_fundraiser= number_format($rebate_so_far, 2, '.', '');
			 ?>
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Period:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br>
							<strong style="color: green;">Total Sale:</strong> <span style="color: green;"><?php echo "$".$total_sale_fundraiser;?></span><br>
							<strong style="color: green;">Total Rebate:</strong><span style="color: green;"><?php echo "$".$total_rebate_fundraiser; ?></span><br>
							<strong style="color: #333;font-size: 14px;">Note: A+B+C+D = Total Rebate and Total Sale.</strong><br>
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
														 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                             
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
							   
                                 if ($item_data['quantity']>99)
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
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= 99 && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > 99 || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
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
										if( $qty <= 99 && empty($new_catalog_order) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > 99 || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
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
							   
                                 if ($item_data['quantity']>99 || !empty($new_catalog_order) && $new_catalog_order == "Yes")
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
			
    </table>
	
    </div>
  </body>
</html>
<?php 
$body= ob_get_clean();
$m_body=stripslashes($body);
		/*$headers = "From: Glow Scented Candles <info@glowglowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: Glow Scented Candles <info@glowglowscentedcandles.com');
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
    $user_email = $user->user_email;
	
    // for simplicity, lets assume that user has typed their first and last name when they sign up
	$character = json_decode(stripslashes($_POST['form_data']));
	
    $recipient="candace@glowscentedcandles.com"; 
	$recipient2="";  
	/* $recipient2="xr4n2b@parsey.com";   */
 /*   $recipient="jatinarora0075@gmail.com";
	$recipient2="shivanideveloper1991@gmail.com";  */ 
	$referral_email=$character[5]->value;
	$org_label_check=$character[0]->label;
	if($org_label_check=="Organization Name")
	{ 
	$check="fund";
	/*	if(!empty($referral_email))
		{*/
			$multiple_recipients = array(
				
				$recipient,
				$recipient2,
				
			);
	/*	}
		else{
			$multiple_recipients = array(
				$user_email,
				$recipient,
				$recipient2
			);
		}*/
	}
	else
	{
		$recipient_main=$recipient;
	/*	if(!empty($referral_email))
		{
			$multiple_recipients = array(
				$user_email,
				$recipient,
				$referral_email
			);
		}
		else{
			$multiple_recipients = array(
				$user_email,
				$recipient
			);
		}*/
		
		
	}
	
	
    $user_full_name =  $character[1]->value.' '.$character[2]->value;
     /*$org=get_user_meta($user_id, "user_registration_input_company", true);*/
	 $org=$character[0]->value;
	 $org_label=$character[0]->label;
	 $sales_label=$character[4]->label;
    // Now we are ready to build our welcome email
    $to = $user_email;
	if($org_label=="Organization Name"){ 
		$subject = "Fund Raising Campaign";
	}
	 elseif($sales_label=="Shipping Address: (required)"){
		  $subject = "Sales Campaign";
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
	   <?php if($org_label=="Organization Name"){ ?>
	   
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
		 <hr style="border-style:dotted;">
		 <p><b>Your Fundraiser ID: <?php echo $user_id; ?></b></p>
		 <?php if(!empty($character[25]->value)) { ?> 
		 <img src="<?php echo $character[25]->value; ?>" width="120" height="120"/><br/>
		 <p style="text-align: center;background: rgb(65,132,243);padding: 8px 18px;display: inline-block;border-radius: 2px;margin: 0;margin-top: 10px;"><a style="color: #ffffff;font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size: 14px;font-weight: 300;line-height: 150%;text-decoration: none;" href="<?php echo $character[25]->value; ?>" target="_BLANK"><?php echo "Download Logo"; ?></a></p>
		 <?php } ?>
		<pre>Do you want an online only fundraiser? : <?php echo $character[26]->value; ?><br>Organization's Name: <?php echo $org; ?><br>Fundraiser's Name:  <?php echo $user_full_name; ?><br>Have you had a Glow Fundraiser before?: <?php  echo $before=$character[27]->value; ?><br>How did you hear about Glow?: <?php  echo $character[3]->value; ?><br>Name of referral: <?php  echo $character[4]->value; ?><br>Referral's email address: <?php  echo $character[5]->value; ?><br>What are your favorite type of candle scents?: <?php  echo $character[6]->value; ?><br>Shipping Address: <?php  echo $character[7]->value; ?><br>City: <?php  echo $character[8]->value; ?><br>State: <?php  echo $character[9]->value; ?><br>Zip: <?php  echo $character[10]->value; ?><br>Phone Number: <?php  echo $character[11]->value; ?><br>Cell Phone: <?php  echo $character[12]->value; ?><br>Email:<?php echo $user_email; ?><br>Number of Participants: <?php  echo $character[15]->value; ?><br>Start Date: <?php  echo $character[16]->value; ?><br>End Date: <?php echo $character[17]->value; ?><br>Fundraising Goal Amount:  <?php echo $character[18]->value; ?><br>Funds Go Toward:  <?php  echo $character[19]->value; ?><br>Selling Goal (# of Items):  <?php  echo $character[20]->value; ?><br>Rebate Pay By ?:  <?php  echo $character[21]->value; ?><br><?php if(!empty($character[22]->value)){ ?>Checks made payable to:  <?php  echo $character[22]->value; } ?><br><?php /*if(!empty($character[22]->value)){ ?>PayPal Email Address:  <?php  echo $character[22]->value; }*/ ?></pre>
		<p><b>Profile Link: <a href="<?php echo ("" . glow_dynamic_url_base('home') . "profile/").$user_id; ?>" target="_blank"><?php echo ("" . glow_dynamic_url_base('home') . "profile/").$user_id; ?></a></b></p>
		<hr style="border-style:dotted;">
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
	  <?php }
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
		else {
	  ?>
	  <div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px 20px;width: 920px;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background:#333;">
				<h1 style="color:#ffffff;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left"> Seller Registered </h1>
		  	</td>
		</tr>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;float:left;">
           <td align="left" style="width: 875px;display: inline-block;">
	   <p>Hi <?php echo $user_full_name =  $character[0]->value.' '.$character[1]->value; ?></p>
      <?php //echo $emailArr3= get_option('name_option_you_want_seller'); ?>
	  <?php $fID=$character[4]->value; 
	  $org=get_user_meta($fID, "user_registration_input_company", true);
	  	$user = get_user_by('id', $user_id);
		if($user)
		{
			$username=$user->user_login;
		}
	  ?>
	   <p style="color: red;"><b>!!PLEASE DO NOT DELETE THIS EMAIL!!</b></p>
		<p> Thank you for booking your GLOW SCENTED CANDLES fundraiser! We are excited to work with you & your group while making the sale as successful, hassle-free and turnkey as possible.</p>
		<p style="color: #000; text-decoration: underline;"><b>CATALOG SALES (if your group is doing online only, please disregard)</b></p>
		<p>Glow will ship your brochures along with kick off product to you within 2 weeks of your sale start date via UPS to your directors address. </p>
      
		<p style="color: #000; text-decoration: underline;"><b>ONLINE "SELLER LOGIN":</b></p>
		<p>Click the following link <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>my-account/?email=<?php echo $user_email; ?>&pass=<?php echo $character[3]->value; ?>" target="_blank"><?php echo esc_url( glow_dynamic_url_base('home') ); ?>my-account</a> to access the Seller Login. Here you can view your up-to-date sales. </p> 
		
		 <?php if(!empty($character[7]->value)) { ?> 
		 <img src="<?php echo $character[7]->value; ?>" width="120" height="120"/><br/>
		 <p style="text-align: center;background: rgb(65,132,243);padding: 8px 18px;display: inline-block;border-radius: 2px;margin: 0;margin-top: 10px;"><a style="color: #ffffff;font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size: 14px;font-weight: 300;line-height: 150%;text-decoration: none;" href="<?php echo $character[7]->value; ?>" target="_BLANK"><?php echo "Download Logo"; ?></a></p>
		 <?php } ?>
		
		<p><b>Your Fundraiser ID: <?php echo $fID; ?></b></p>
         <p><b>Group Name: </b><?php echo $org; ?></p>
		  <p><b>Seller Goal: (Dollar Amount) : </b><?php echo "$".$character[5]->value; ?></p>
		 <p><b>Description: </b><?php echo $character[6]->value; ?></p>
		 
		 <p><b>Login Details: </b></p>
         <p><b>Email Address: </b> <?php echo $user_email; ?> </p>
	     <p><b>Password: </b> <?php echo $character[3]->value; ?> </p>
		 <p><b>Profile Link: <a href="<?php echo ("" . glow_dynamic_url_base('home') . "seller/user/").$username; ?>" target="_blank"><?php echo ("" . glow_dynamic_url_base('home') . "seller/user/").$username; ?></a></b></p>
		 <pre>Seller's Name:  <?php echo $user_full_name =  $character[0]->value.' '.$character[1]->value; ?><br>Email:<?php echo $user_email; ?><br></pre>
		<hr style="border-style:dotted;">
		<ul>
 	<li>Boost sales and reach everyone with or online shopping option. This is the perfect solution to out of town family & friends. Customer pays for their order online via credit card, their order drop ships directly to the customer within 7 days.</li>
 	<li>Glow issues (ONE) profit check to the fundraiser director for easy disbursing of profits/funds earned by seller. YES, sales are tracked by sellers, do not worry!</li>
 	<li>Online sales earn 40% profits, due to shipping and direct drop ship.</li>
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
		<?php }?>
</body>
</html>
	<?php 
	$body= ob_get_clean();
	$m_body=stripslashes($body);
	$headers = array('Content-Type: text/html; charset=UTF-8','From: Glow Scented Candles <info@glowglowscentedcandles.com');
	/* Referral Recipients */
	if(!empty($referral_email))
	{
		if (wp_mail($referral_email, $subject, $m_body, $headers)) 
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
		if (wp_mail($recipient_main, $subject, $m_body, $headers)) {
		  error_log("email has been successfully sent to user whose email is " . $recipient_main);
		}
		else
		{
		  error_log("email failed to sent to user whose email is " . $recipient_main);
		}
	}
	/* Registered Recipients */
	if (wp_mail($user_email, $subject, $m_body, $headers)) {
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

  // THE ONLY DIFFERENCE IS THIS LINE
  add_action('user_register', 'send_welcome_email_to_new_user');

 /* $checker='[{"value":"Gargiytt","field_type":"text","label":"First Name","field_name":"first_name"},{"value":"Aroratty","field_type":"text","label":"Last Name","field_name":"last_name"},{"value":"web solution","field_type":"text","label":"Organisation Name","field_name":"input_company"},{"value":"Facebook","label":"How did you hear about Glow?","field_name":"reference"},{"value":"gttt","field_type":"text","label":"Name of referral","field_name":"referral_name"},{"value":"Fresh/Clean","label":"What are your favorite type of candle scents?","field_name":"favorite_candle_scents"},{"value":"gargiarora75@gmail.com","field_type":"email","label":"User Email","field_name":"user_email"},{"value":"@admin1234","field_type":"password","label":"User Password","field_name":"user_pass"},{"value":"10","field_type":"text","label":"Number of Participants","field_name":"participants_no"},{"value":"2020-06-05","field_type":"text","label":"Start Date","field_name":"start_date"},{"value":"2020-06-17","field_type":"text","label":"End Date","field_name":"end_date"},{"value":"44","field_type":"number","label":"Fundraising Goal","field_name":"fr_goal"},{"value":"1000","field_type":"text","label":"Funds Go Toward","field_name":"fund_destination"},{"value":"1000","field_type":"text","label":"Selling Goal (# of Items)","field_name":"selling_goal"},{"value":"Jatin NGO","field_type":"text","label":"Checks Made Payable To","field_name":"check_payable"},{"value":"Yes","field_type":"radio","label":"Have you had a Glow Fundraiser before?","field_name":"radio_1534260773"}]';
  $character = json_decode($checker);
  echo $character[0]->value;*/
  	 /* Custom Fields Fundraiser registered */ 
	function custom_fundraiser_fields($user_id) {
    $user = get_userdata($user_id);
    $user_email = $user->user_email;
    // for simplicity, lets assume that user has typed their first and last name when they sign up
	$character = json_decode(stripslashes($_POST['form_data']));
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
  add_action('user_register', 'custom_fundraiser_fields',10, 1);
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
function setup_theme_admin_menus() 
{
	add_menu_page('Email Settings', 'Email Settings', 'manage_options', 'edit_email', 'theme_edit_email_options');
	add_menu_page('Fundraiser Operations', 'Fundraiser Operations', 'manage_options', 'fundraiser_operations', 'theme_fundraiser_operations_options');
	add_menu_page('Fundraiser Outstanding', 'Fundraiser Outstanding', 'manage_options', 'fundraiser_outstanding', 'theme_fundraiser_outstanding_options');
	add_menu_page('Send Check Orders', 'Send Check Orders', 'manage_options', 'send_check_orders', 'theme_fundraiser_send_check_orders');
	add_menu_page('Email Tracking', 'Email Tracking', 'manage_options', 'email_tracking', 'theme_fundraiser_email_tracking');
	add_menu_page('Booking List', 'Booking List', 'manage_options', 'booking_list', 'theme_fundraiser_booking_track');

}
add_action("admin_menu", "setup_theme_admin_menus");
function save_wp_editor_fields(){
    global $post;
    update_post_meta($post->ID, 'custom_editor_box', $_POST['custom_editor_box']);
}
add_action( 'save_post', 'save_wp_editor_fields' );

function theme_edit_email_options()
{
?>
	<style>
	.inputUser
	{
		margin-bottom:20px;
	}
	.input_fields_wrap
	{
		margin-left:40px;
	}
	.toplevel_page_view_email
	{
		background-color: #EEE;
	}
	</style>
	<div class="row auction_data">	
		<div class="col-sm-12">
			<div class="panel panel-default panel-table">
				<div class="panel-heading" style="padding: 20px 0px;font-size: 25px;"> Fundraiser Email Notification
					<!--<span class="panel-subtitle">This is a custom datable layout</span>-->
				</div>
				<div class="panel-body">
					<div id="table2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<div class="row be-datatable-body">
							<div class="col-sm-12">
							
							<table id="table2" class="table table-striped table-hover table-fw-widget dataTable no-footer" role="grid" aria-describedby="table2_info">
									<thead>
										
									</thead>
									<tbody>
									
									 <?php/*  wp_nonce_field('update-options') */?>
								<tr>
								<?php 
								$editor_id = 'customerCleanerMail';
								if (isset($_POST[$editor_id])){ update_option('name_option_you_want', $_POST[$editor_id]);  }
								$emailArr= get_option('name_option_you_want'); 
								if(!empty($emailArr))
								{
									$default_content=$emailArr;
								}
								else
								{
									$default_content='<p>Mail formate</p>';
								}
								echo '<form action="" class="booked-settings-form" method="post">';

								$option_name='customerCleanerMail';
								$default_content=html_entity_decode($default_content);
								$default_content=stripslashes($default_content);
								wp_editor( $default_content, $editor_id,array('textarea_name' => $option_name,'media_buttons' => true,'editor_height' => 350,'teeny' => true)  );
								submit_button('Save', 'primary');   echo '</form>';

								?>
								</tr>
								
								
									<tr class="auctionDetail">
												<td class="colHeading"></td>
												<td class="sorting_1">
													<!--<input type="hidden" name="action" value="update" />
													<input type="hidden" name="page_options" value="custom_editor_box" />
													<input type="submit" name="submit" value="Save"/>-->
												</td>
									</tr>
								
							
								</tbody>
								</table>
									
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<div class="row auction_data">	
		<div class="col-sm-12">
			<div class="panel panel-default panel-table">
				<div class="panel-heading" style="padding: 20px 0px;font-size: 25px;"> Seller Email Notification
					<!--<span class="panel-subtitle">This is a custom datable layout</span>-->
				</div>
				<div class="panel-body">
					<div id="table2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<div class="row be-datatable-body">
							<div class="col-sm-12">
							
							<table id="table2" class="table table-striped table-hover table-fw-widget dataTable no-footer" role="grid" aria-describedby="table2_info">
									<thead>
										
									</thead>
									<tbody>
									
									 <?php/*  wp_nonce_field('update-options') */?>
								<tr>
								<?php 
								$editor_id = 'customerCleanerMail2';
								if (isset($_POST[$editor_id])){ update_option('name_option_you_want_seller', $_POST[$editor_id]);  }
								$emailArr= get_option('name_option_you_want_seller'); 
								if(!empty($emailArr))
								{
									$default_content=$emailArr;
								}
								else
								{
									$default_content='<p>Mail formate</p>';
								}
								echo '<form action="" class="booked-settings-form" method="post">';

								$option_name='customerCleanerMail2';
								$default_content=html_entity_decode($default_content);
								$default_content=stripslashes($default_content);
								wp_editor( $default_content, $editor_id,array('textarea_name' => $option_name,'media_buttons' => true,'editor_height' => 350,'teeny' => true)  );
								submit_button('Save', 'primary');   echo '</form>';

								?>
								</tr>
								
								
									<tr class="auctionDetail">
												<td class="colHeading"></td>
												<td class="sorting_1">
													<!--<input type="hidden" name="action" value="update" />
													<input type="hidden" name="page_options" value="custom_editor_box" />
													<input type="submit" name="submit" value="Save"/>-->
												</td>
									</tr>
								
							
								</tbody>
								</table>
									
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<div class="row auction_data">	
		<div class="col-sm-12">
			<div class="panel panel-default panel-table">
				<div class="panel-heading" style="padding: 20px 0px;font-size: 25px;"> Sales Email Notification
					<!--<span class="panel-subtitle">This is a custom datable layout</span>-->
				</div>
				<div class="panel-body">
					<div id="table2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<div class="row be-datatable-body">
							<div class="col-sm-12">
							
							<table id="table2" class="table table-striped table-hover table-fw-widget dataTable no-footer" role="grid" aria-describedby="table2_info">
									<thead>
										
									</thead>
									<tbody>
									
									 <?php/*  wp_nonce_field('update-options') */?>
								<tr>
								<?php 
								$editor_id = 'customerCleanerMail3';
								if (isset($_POST[$editor_id])){ update_option('name_option_you_want_sales', $_POST[$editor_id]);  }
								$emailArr= get_option('name_option_you_want_sales'); 
								if(!empty($emailArr))
								{
									$default_content=$emailArr;
								}
								else
								{
									$default_content='<p>Mail formate</p>';
								}
								echo '<form action="" class="booked-settings-form" method="post">';

								$option_name='customerCleanerMail3';
								$default_content=html_entity_decode($default_content);
								$default_content=stripslashes($default_content);
								wp_editor( $default_content, $editor_id,array('textarea_name' => $option_name,'media_buttons' => true,'editor_height' => 350,'teeny' => true)  );
								submit_button('Save', 'primary');   echo '</form>';

								?>
								</tr>
								
								
									<tr class="auctionDetail">
												<td class="colHeading"></td>
												<td class="sorting_1">
													<!--<input type="hidden" name="action" value="update" />
													<input type="hidden" name="page_options" value="custom_editor_box" />
													<input type="submit" name="submit" value="Save"/>-->
												</td>
									</tr>
								
							
								</tbody>
								</table>
									
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<?php
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
	$group_name=get_user_meta( $slrID, 'user_registration_input_company', true );
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
					$user_email,
					$referral_email,
					$seller_email
				);
			
	}
	else if(empty($seller_email) && !empty($referral_email))
	{
		$multiple_recipients = array(
					$user_email,
					$referral_email
				);
			
	}
	else if(!empty($seller_email) && empty($referral_email))
	{
		$multiple_recipients = array(
					$user_email,
					$seller_email
				);
			
	}
	else
	{
		$multiple_recipients = $user_email;
			
	}
    $subject = "Glow Scented Candles - Fundraiser Order";
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
		   <h1 style="color: #333;font-size: 25px;font-weight: 600;line-height: 100%;padding-bottom: 10px;"> Glow Scented Candles - Fundraiser Order </h1>
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
	$headers = array('Content-Type: text/html; charset=UTF-8','From: Glow Scented Candles <info@glowglowscentedcandles.com');
    if (wp_mail($multiple_recipients, $subject, $m_body, $headers)) {
      error_log("email has been successfully sent to user whose email is " . $user_email);
    }else{
      error_log("email failed to sent to user whose email is " . $user_email);
    }
	
}
  }
  add_action( 'woocommerce_payment_complete', 'custom_new_order_email', 10, 1 );
/* add_action( 'woocommerce_checkout_order_processed', 'custom_new_order_email',1,1); */
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
			   $org=get_user_meta( $u_id , 'user_registration_input_company' , true );
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
                        
                             
                                 if ($item_data['quantity']>99){
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
                        ?>
						<tr>
             <td align="left" style="padding: 30px 30px;">
			 <div class="text-left" style="float:left;">
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Glow Scented Candles:</strong><br>
							4628 Industrial Street<br>
							Rowlett, TX 75088<br>
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
			$total_sale_fundraiser= number_format($total_sale_so_far, 2, '.', ''); 
			$total_rebate_fundraiser= number_format($rebate_so_far, 2, '.', '');
			 ?>
				<address style="margin-bottom: 20px;font-style: normal;line-height: 1.428571429;">
							<strong>Name of referral:</strong> <?php echo $referral; ?><br>
							<strong>Participants:</strong> <?php echo $Participants; ?><br>
							<strong>Period:</strong> <?php echo $sd; ?> to <?php echo $ed; ?><br>
							<strong style="color: green;">Total Sale:</strong> <span style="color: green;"><?php echo "$".$total_sale_fundraiser;?></span><br>
							<strong style="color: green;">Total Rebate:</strong><span style="color: green;"><?php echo "$".$total_rebate_fundraiser; ?></span><br>
							<strong style="color: #333;font-size: 14px;">Note: A+B+C+D = Total Rebate and Total Sale.</strong><br>
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
														 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                             
                                 if ($item_data['quantity']>99)
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
							   
                                 if ($item_data['quantity']>99)
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
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= 99 && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > 99 || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
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
										if( $qty <= 99 && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > 99 || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
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
							   
                                 if ($item_data['quantity']>99 || !empty($new_catalog_order) && $new_catalog_order == "Yes")
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
		
		
		
    </table>
	
    </div>
  </body>
</html>
<?php 
$body= ob_get_clean();
$m_body=stripslashes($body);
		/*$headers = "From: Glow Scented Candles <info@glowglowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: Glow Scented Candles <info@glowglowscentedcandles.com');
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
											/* Active/Inactive */	
											$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
								
									 
										 if ($item_data['quantity']>99){
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
									
										 
												 if ($item_data['quantity']>99)
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

								  } } }
		
		if($action==1)
		{
		update_user_meta( $ID , 'user_rebate' , $rebate_so_far );
		}
		update_user_meta( $ID , 'user_status' , $action );
		/*echo "Your Status has been successfully updated.";*/
		wp_send_json_success(array(
		'action' => $action
		));
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
			if(strlen($_POST['fundraiser_field'])<6 || strlen($_POST['fundraiser_field'])>6)
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
						
					}
					else
					{
						
					}
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
			$group_name=get_user_meta($ID, "user_registration_input_company", true);
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
	 $group_name=get_user_meta( $id, 'user_registration_input_company', true );
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

wp_redirect( ('' . glow_dynamic_url_base('home') . 'my-account'), 301 ); 
  //exit;
    }
	if ( is_page('seller-reports') && ! is_user_logged_in() ) {

wp_redirect( ('' . glow_dynamic_url_base('home') . 'my-account'), 301 ); 
  //exit;
    }
	
	if ( is_page('seller-dashboard') && ! is_user_logged_in() ) {

wp_redirect( ('' . glow_dynamic_url_base('home') . 'my-account'), 301 ); 
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
		session_start();
			$fID=$_SESSION["fridID"];
			$user_info = get_userdata($_SESSION["fridID"]);
            $email = $user_info->user_email;
		if (empty($email) && !empty($_SESSION["fridID"])) 
		{ 
		?>
			<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
				<ul class="woocommerce-error" role="alert">
					<li><?php echo $_SESSION["fridID"]." FUNDRAISER NOT FOUND";?></li>
				</ul>
			</div>
			<!--<div class="wc-seller-buyer-cookie-popup show">
				<div class="wc-popup-body">
					<h2><?php echo $_SESSION["fridID"]." FUNDRAISER NOT FOUND"; ?></h2>
					<span class="wc-popup-close">Or, Cancal</span>
				</div>
			</div>-->
		<?php
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
		/*$headers = "From: Glow Scented Candles <info@glowglowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: Glow Scented Candles <info@glowglowscentedcandles.com');
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
		/*$to = "jatinarora0075@gmail.com";*/
		date_default_timezone_set('America/Chicago');
		/*$current_time = date('Y-m-d H:i:s');	*/				 
		$current_date = date('Y-m-d H:i:s a');
		$args=array('post_title'=>'Close Out #'.$ID, 'post_type'=>'close_out', 'post_status' => 'publish', 'post_author' => $ID);
	    $post_id = wp_insert_post($args,  true);
	
		if(!empty($post_id))
	    {
			update_post_meta( $post_id , 'close out_fundraiser_id', $ID);
		    update_post_meta( $post_id , 'close out_fundraiser_date', $current_date); 
			update_user_meta($ID, "user_close_out_value", 1);
			update_user_meta($ID, "user_close_out_date_inactive", $current_date);
			update_user_meta($ID, 'user_action_status' , 2 );
		}
		
		$org=get_user_meta( $ID , 'user_registration_input_company' , true );
		$f=get_user_meta( $ID , 'first_name' , true );		
		$l=get_user_meta( $ID , 'last_name' , true );	
		$user_info = get_userdata($ID);
        $email = $user_info->user_email;
        $to = "candace@glowscentedcandles.com";  
	/*    $to = "jatinarora0075@gmail.com";   */
	   /*  $to = "shivanideveloper1991@gmail.com"; */
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
		/*$headers = "From: Glow Scented Candles <info@glowglowscentedcandles.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
		$headers = array('Content-Type: text/html; charset=UTF-8','From: Glow Scented Candles <info@glowglowscentedcandles.com');
		$mailSuc= wp_mail( $to, $subject, $m_body, $headers );
	 	if($mailSuc){
		echo "Close out request sent.";
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
			
			if( in_array( 'fundraiser', (array) $user->roles ) ) 
			{
				session_start();
				$_SESSION['sellername']='Catlog';
			}
		}
	}
	 if(!empty($_SESSION['sellername']) && $_SESSION['sellername']=='Catlog') 
	{
	 ob_start();	
?>
<style>
.wc_payment_methods .wc_payment_method.payment_method_cheque
{
	display:block;
}
</style>
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
  get_currentuserinfo();

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
	get_currentuserinfo();

	if (is_user_logged_in())
	{
		
		$user_id = $current_user->ID;
		$wdm_selected_cart_key='cart-restore';
		/*$cart_content = get_user_meta($user_id, $wdm_selected_cart_key, true);*/
		$cart_content = get_user_meta($user_id, 'cart-restore', true);
		  // clear current cart, incase you want to replace cart contents, else skip this step
		 $woocommerce->cart->empty_cart();
		session_start();
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
	 <?php $full_arr_t=array();
	 $c=0;
	        foreach ( $all_meta_for_users as $all_meta_for_user ) { 
							 $total_sale_so_far=0;
							  $rebate_so_far=0;
							  $item_qty =0;
							$IDD=$all_meta_for_user->ID; 
						   $seller_first=$all_meta_for_user->user_firstname;
						   $seller_last=$all_meta_for_user->user_lastname;
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99)
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
								else if ($frID3 == $order_customer_id) 
								{ 
									/*$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if($order_type=="Online") 
									{*/
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
                        
                             
                                 if ($item_data['quantity']>99)
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
                               
								/*}*/
								}
								else{}
								
								} } }
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
						$full_arr_t=array();
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
							$item_qty=0;
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
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
														 if ($item_data['quantity']>99)
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
    wp_register_script('ajax-login-script', get_template_directory_uri() . '/js/ajax-login-script.js', array('jquery') );
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
			
			if ( in_array( 'fundraiser', (array) $user_signon->roles ) ) {
				$redirect_url=("" . glow_dynamic_url_base('home') . "fundraiser-dashboard/");
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
add_action( 'template_include', function( $template ) 
{
	if ( get_query_var( 'profile' ))
	{
		return get_template_directory() . '/template-profile.php';
	}
	else if ( get_query_var( 'seller' ))
	{
		return get_template_directory() . '/template-sellerprofile.php';
	}
	else if ( get_query_var( 'user' ))
	{
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
		$org=get_user_meta( $IDG , 'user_registration_input_company' , true );
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
	//session_start();
	$_SESSION["fridID"] = $fund_code;
	
}
/* Ajax for auto fundraiser code and Seller Name */
add_action('wp_ajax_nopriv_wdm_auto_fundraiser_code2', 'wdm_auto_fundraiser_code2'); 
add_action('wp_ajax_wdm_auto_fundraiser_code2', 'wdm_auto_fundraiser_code2');
function wdm_auto_fundraiser_code2()
{
	$fund_code2=$_POST['fund_id2'];
	session_start();
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
	function profile_image_resize_crop ( $src, $w, $h, $dest = null, $override = true, $createNewIfExists = true ) {
    	$ext = array_pop ( explode ('.', $src) );
    	$filenameSrc = str_replace (".$ext", '', basename($src) );
    	$filename = "{$filenameSrc}-{$w}X{$h}";
    	$arrayUploadPath = wp_upload_dir();
    	$fileUploadSubDir = "/profile/";
    	$fileUploadDir = $arrayUploadPath['basedir'] . "/profile/";
    	
    	if(is_null($dest)) $dest = $fileUploadDir;
    	
    	$i = null;
    	if( ! $override && $createNewIfExists ) {
    		$i = 0;
    		while ( file_exists("$dest$filename-$i.jpg") ) $i++;
    		$i = '-' . $i;
    	}
    	
    	$fileFullPath = "$dest$filename$i.jpg";
    	$fileFullUrl = $arrayUploadPath['baseurl'] . $fileUploadSubDir . $filename.$i .'.jpg';
    	
    	//return cached file if $override == false and file's already there
    	if( ! $override && file_exists($fileFullPath) ) return $fileFullUrl;
    	
    	if( $override ) @unlink($fileFullPath);
    	
    	switch ($ext) {
    		case 'jpg':
    		case 'jpeg' : $image = imagecreatefromjpeg($src); break;
    		case 'gif' : $image = imagecreatefromgif($src); break;
    		case 'png' : $image = imagecreatefrompng($src); break;
    		case 'wbmp' :
    		case 'bmp': $image = imagecreatefromwbmp($src); break;
    		default: $image = imagecreatefromgd2($src);
    	}
    	
    	$width = imagesx($image);
    	$height = imagesy($image);
    
    	$original_aspect = $width / $height;
    	$thumb_aspect = $w / $h;
    	
    	if ( $original_aspect >= $thumb_aspect ) {
    		if( $width > $w ) {
    			$new_height = $h;
    			$new_width = $width / ($height / $h);
    		}else{
    			$new_height = $height;
    			$new_width = $width;
    		}
    		
    	} else {
    	   if ( $width > $w ) {
    		   $new_width = $w;
    		   $new_height = $height / ($width / $w);
    	   } else {
    		   $new_width = $width;
    		   $new_height = $height;
    	   }
    	}
    	
    	$thumb = imagecreatetruecolor($w, $h);
    	$bg = imagecolorallocate($thumb, 255, 255, 255);
    	imagefill($thumb, 0, 0, $bg);
    
    	imagecopyresampled($thumb,
    					   $image,
    					   0 - ($new_width - $w) / 2,
    					   0 - ($new_height - $h) / 2,
    					   0, 0,
    					   $new_width, $new_height,
    					   $width, $height);
    	
    	imagepng($thumb, $fileFullPath, 9);
    	imagedestroy($image);
    	
    	return $fileFullUrl;
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
			$group_name=get_user_meta($ID, "user_registration_input_company", true);
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
			$group_name=get_user_meta($ID, "user_registration_input_company", true);
			
					    $f=get_user_meta( $ID , 'first_name' , true );		
						$l=get_user_meta( $ID , 'last_name' , true );	
						$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
						$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
						$org=get_user_meta( $ID , 'user_registration_input_company' , true );
						$goal_amount=get_user_meta( $ID , 'user_registration_fr_goal' , true );
						$fund_desc=get_user_meta($ID,'fund_profile_description',true);
						$fund_image=get_user_meta($ID,'fund_profile_image',true);
						$user_registration_fund_destination=get_user_meta($ID,'user_registration_fund_destination',true);
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
									 $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
                        
                             
                                 if ($item_data['quantity']>99){
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
                        
                             
                                 if ($item_data['quantity']>99){
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
    top: 340px;
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
    font-size: 27px;
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
    top: 213px;
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
	padding-left: 0;
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
    font-size: 25px;
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
}
@media only screen and (max-width: 420px)
{
	#brave_popup_12975__step__0 #brave_element--N3sYSN80c2sV-aMSMJ9 {
        top: 300px;
}
#brave_popup_12975__step__0 #brave_element--N3sYSN7DAn8xy7o0Mng .brave_element__text_inner {
    font-size: 23px;
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
                                          <div class="brave_element__image_inner">
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
										<?php $width=round($rebate_so_far / ($goal_amount / 100),2); ?>
											<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $width."%"; ?>;"></div>
										</div>
                                         <div class="sc-dqBHgY ccbzhW">
											<div class="sc-hwwEjo eoCznv">
											   <div width="55" class="sc-kTUwUJ jZDald">$<?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', '')." "; ?></div>
											   <div class="sc-kTUwUJ eYHKCV"> / $<?php echo $goal_amount; ?></div>
											</div>
											<div id="#fundraising_dates" class="sc-kTUwUJ eYHKCV"><?php  echo $newDate; ?> - <?php  echo $newDate2; ?></div>
										 </div>
										 
                                       </div>
                                    </div>
                                 </div>
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
                                          <div class="brave_element__text_inner"><?php if(!empty($user_registration_fund_destination)){ echo $user_registration_fund_destination; } else{ "Help us reach our goal! Thank you for the support!"; } ?></div>
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
                <option frdata="<?php echo get_user_meta( $sellerUser->ID, 'user_registration_seller_fundraiser', true ); ?>" value="<?php echo $sellerUser->ID; ?>" <?php $s_f=$sellerUser->first_name; $s_l= $sellerUser->last_name; if(strtolower($str_n[0]) == strtolower($s_f) && strtolower($str_n[1])== strtolower($s_l) && $check_result==1) { $open=1;?> id="selectSeller" <?php } ?>><?php echo $sellerUser->first_name; ?> <?php echo $sellerUser->last_name; ?></option>
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

add_role( 'representative', 'Representative', get_role( 'editor' )->capabilities );
include_once( 'representative.php' );	

function representative_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for subscribers
        if (in_array('representative', $user->roles)) {
            // redirect them to another URL, in this case, the homepage 
            $redirect_to =  ("" . glow_dynamic_url_base('home') . "reprenstative-dashboard/");
        }
    }

    return $redirect_to;
}

add_filter( 'login_redirect', 'representative_login_redirect', 10, 3 );

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
			<?php /*?><div class="form-outline  col-2">
				<label class="form-label" for="typeNumber1">Quantity</label>
				<input type="number" id="typeNumber" class="form-control" />
			</div><?php */?>
			<div class=" text-center col-2">
				
				<?php /*?><button type="button" class="btn btn-dark mx-auto text-white">Add to cart</button> <?php */?>
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
				/* Active/Inactive */	
				$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
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
							if( $qty <= 99 ){ 
								$subtot=$order->get_subtotal(); 
								$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
								$total_sale_so_far_Catlog+=$subtot-$to_widthdraw; 
							}  
							else if($qty > 99 ){ 
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
		$org = get_user_meta( $fundID , 'user_registration_input_company' , true );
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

?>