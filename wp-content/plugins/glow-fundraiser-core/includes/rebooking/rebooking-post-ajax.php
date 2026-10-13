<?php
/* Rebooking Ajax */
add_action('wp_ajax_nopriv_wp_rebooking_ajax', 'wp_rebooking_ajax'); 
add_action('wp_ajax_wp_rebooking_ajax', 'wp_rebooking_ajax');
function wp_rebooking_ajax()
{
	if(isset($_POST['rebook_id']) && isset($_POST['yes_action']) && $_POST['yes_action']=="yes")
	{
		$current_date="";
		$date_time="";
		$user_id=$_POST['rebook_id'];
		$action=$_POST['yes_action'];
		date_default_timezone_set('America/Chicago');
		$current_date = date('Y-m-d');
		$current_time = date('H:i:s');
		$date_time=$current_date."_".$current_time;
		if(!empty($action))
		{
			
			update_user_meta($user_id, "rebook_yes" , $action);
		}
		update_user_meta($user_id, "rebook_date", $current_date);
		$user_info = get_userdata($user_id);
		$userEmail = $user_info->user_email;
		$prefix="rebook_".$date_time."_";
		$new_email= $prefix.$userEmail;
		$args = array(
			'ID'         => $user_id,
			'user_email' => esc_attr( $new_email )
		);
		$user_data = wp_update_user( $args );
	
		if( !is_wp_error($user_data) ) {
			echo "success ";
			echo $date_time;
			wp_logout();
		} else {
			echo $user_data->get_error_message();
		}
		
		exit;
	}
}

/* Seller Rebooking Ajax */
add_action('wp_ajax_nopriv_seller_wp_rebooking_ajax', 'seller_wp_rebooking_ajax'); 
add_action('wp_ajax_seller_wp_rebooking_ajax', 'seller_wp_rebooking_ajax');
function seller_wp_rebooking_ajax()
{
	if(isset($_POST['rebook_id']) && isset($_POST['yes_action']) && $_POST['yes_action']=="yes")
	{
		$current_date="";
		$date_time="";
		$user_id=$_POST['rebook_id'];
		$action=$_POST['yes_action'];
		date_default_timezone_set('America/Chicago');
		$current_date = date('Y-m-d');
		$current_time = date('H:i:s');
		$date_time=$current_date."_".$current_time;
		if(!empty($action))
		{
			
			update_user_meta($user_id, "rebook_yes" , $action);
		}
		update_user_meta($user_id, "rebook_date", $current_date);
		$user_info = get_userdata($user_id);
		$userEmail = $user_info->user_email;
		update_user_meta($user_id, "rebook_email", $userEmail);
		$prefix="rebook_".$date_time."_";
		$new_email= $prefix.$userEmail;
		$args = array(
			'ID'         => $user_id,
			'user_email' => esc_attr( $new_email )
		);
		$user_data = wp_update_user( $args );
	
		if( !is_wp_error($user_data) ) {
			echo $user_id;
			wp_logout();
		} else {
			echo $user_data->get_error_message();
		}
		
		exit;
	}
}
?>