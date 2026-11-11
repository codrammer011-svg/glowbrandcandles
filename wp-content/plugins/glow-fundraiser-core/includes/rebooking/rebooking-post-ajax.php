<?php
/* Rebooking Ajax */
add_action('wp_ajax_nopriv_wp_rebooking_ajax', 'wp_rebooking_ajax'); 
add_action('wp_ajax_wp_rebooking_ajax', 'wp_rebooking_ajax');
function wp_rebooking_ajax()
{
    // Existing callers must use the date/confirmation form provided by Core.
    Glow_FC_Rebooking::legacy_request();
}

/* Seller Rebooking Ajax (retired) */
add_action('wp_ajax_nopriv_seller_wp_rebooking_ajax', 'seller_wp_rebooking_ajax');
add_action('wp_ajax_seller_wp_rebooking_ajax', 'seller_wp_rebooking_ajax');
function seller_wp_rebooking_ajax()
{
    // The old handler renamed ANY user's email from an unauthenticated request.
    // Sellers now re-book from the dashboard form (Glow_FC_Seller_Rebooking).
    wp_send_json_error(array('message' => 'Please reload your seller dashboard and use Re-book to enter your new Fundraiser ID.'), 409);
}
