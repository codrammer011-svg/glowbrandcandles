<?php
/*
	Template Name: Edit Fundraiser Info
*/
get_header();

global $l; // ensure theme global is available

// Get and validate user id from query param
$u_id = isset($_GET['frid']) ? absint($_GET['frid']) : 0;
if (!$u_id || !get_user_by('id', $u_id)) {
	wp_safe_redirect(home_url('/'));
	exit;
}

$fpid = get_user_meta($u_id, 'fundraiser_post_id', true);

// Handle form submit
if (isset($_POST['submit'])) {
	// CSRF + capability
	// if (
	// 	!isset($_POST['_wpnonce']) ||
	// 	!wp_verify_nonce($POST['_wpnonce'], 'edit_fundraiser_info' . $u_id)
	// ) {
	// 	wp_safe_redirect(add_query_arg('msg', 'invalid_nonce', get_permalink()));
	// 	exit;
	// }

	$current_user_id = get_current_user_id();
	if (!current_user_can('edit_user', $u_id) && (int) $current_user_id !== (int) $u_id) {
		wp_safe_redirect(add_query_arg('msg', 'forbidden', get_permalink()));
		exit;
	}

	// Helpers
	$get = static function (string $key, $default = '') {
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	};
	$sanitize_text = static function ($val) {
		return is_array($val) ? implode(', ', array_map('sanitize_text_field', $val)) : sanitize_text_field($val);
	};

	// Sanitize inputs
	$name_of_school_business          = $sanitize_text($get('name_of_school_business'));
	$input_box_phone                  = $sanitize_text($get('input_box_phone'));
	$street_address                   = $sanitize_text($get('street_address'));
	$City2                            = $sanitize_text($get('City2'));
	$select_state                     = $sanitize_text($get('select_state'));
	$input_box_zip                    = $sanitize_text($get('input_box_zip'));
	$fundraiser_preference            = $sanitize_text($get('fundraiser_preference'));
	$participants_no                  = $sanitize_text($get('participants_no'));
	$start_date                       = $sanitize_text($get('start_date'));
	$end_date                         = $sanitize_text($get('end_date'));
	$first_name                       = $sanitize_text($get('first_name'));
	$last_name                        = $sanitize_text($get('last_name'));
	$input_box_1595312762             = $sanitize_text($get('input_box_1595312762'));
	$chairpersonaddress               = $sanitize_text($get('chairpersonaddress'));
	$City                             = $sanitize_text($get('City'));
	$State                            = $sanitize_text($get('State'));
	$Zip_Code                         = $sanitize_text($get('Zip_Code'));
	$user_email                       = isset($_POST['user_email']) ? sanitize_email($_POST['user_email']) : '';
	$Brochures_will_be_shipped        = $sanitize_text($get('Brochures_will_be_shipped'));
	$radio_1534260773                 = $sanitize_text($get('radio_1534260773'));
	$reference                        = $sanitize_text($get('reference'));
	$referral_name                    = $sanitize_text($get('referral_name'));
	$referral_email                   = isset($_POST['referral_email']) ? sanitize_email($_POST['referral_email']) : '';
	$favorite_candle_scents           = $sanitize_text($get('favorite_candle_scents'));
	$fr_goal                          = isset($_POST['fr_goal']) ? floatval($_POST['fr_goal']) : '';
	$selling_goal                     = $sanitize_text($get('selling_goal'));
	$reason_for_fundraising           = wp_kses_post($get('reason_for_fundraising'));
	$input_box_1693293590             = $sanitize_text($get('input_box_1693293590'));
	$check_box_new                    = !empty($_POST['check_box_new']) ? 'Yes' : '';
	$image_url_profile                = isset($_POST['image_url_profile']) ? esc_url_raw($_POST['image_url_profile']) : '';

	// Save meta (only update on presence; adjust to your desired behavior)
	$maybe_update = static function ($user_id, string $meta_key, $value) {
		update_user_meta($user_id, $meta_key, $value);
	};

	$maybe_update($u_id, 'user_registration_name_of_school_business', $name_of_school_business);
	$maybe_update($u_id, 'user_registration_input_box_phone', $input_box_phone);
	$maybe_update($u_id, 'user_registration_street_address', $street_address);
	$maybe_update($u_id, 'user_registration_City2', $City2);
	$maybe_update($u_id, 'user_registration_select_state', $select_state);
	$maybe_update($u_id, 'user_registration_input_box_zip', $input_box_zip);
	$maybe_update($u_id, 'user_registration_fundraiser_preference', $fundraiser_preference);
	$maybe_update($u_id, 'user_registration_participants_no', $participants_no);
	$maybe_update($u_id, 'user_registration_start_date', $start_date);
	$maybe_update($u_id, 'user_registration_end_date', $end_date);
	$maybe_update($u_id, 'first_name', $first_name);
	$maybe_update($u_id, 'last_name', $last_name);
	$maybe_update($u_id, 'user_registration_input_box_1595312762', $input_box_1595312762);
	$maybe_update($u_id, 'user_registration_chairpersonaddress', $chairpersonaddress);
	$maybe_update($u_id, 'user_registration_City', $City);
	$maybe_update($u_id, 'user_registration_State', $State);
	$maybe_update($u_id, 'user_registration_Zip_Code', $Zip_Code);
	$maybe_update($u_id, 'user_email', $user_email);
	$maybe_update($u_id, 'user_registration_Brochures_will_be_shipped', $Brochures_will_be_shipped);
	$maybe_update($u_id, 'user_registration_radio_1534260773', $radio_1534260773);
	$maybe_update($u_id, 'user_registration_reference', $reference);
	$maybe_update($u_id, 'user_registration_referral_name', $referral_name);
	$maybe_update($u_id, 'user_registration_referral_email', $referral_email);
	$maybe_update($u_id, 'user_registration_favorite_candle_scents', $favorite_candle_scents);
	$maybe_update($u_id, 'user_registration_fr_goal', $fr_goal);
	$maybe_update($u_id, 'user_registration_selling_goal', $selling_goal);
	$maybe_update($u_id, 'user_registration_reason_for_fundraising', $reason_for_fundraising);
	$maybe_update($u_id, 'user_registration_input_box_1693293590', $input_box_1693293590);
	$maybe_update($u_id, 'user_registration_check_payable', $input_box_1693293590);
	$maybe_update($u_id, 'check_payable', $input_box_1693293590);
	$maybe_update($u_id, 'user_registration_input_company', $name_of_school_business);
	$maybe_update($u_id, 'user_registration_check_box_new', $check_box_new);
	$maybe_update($u_id, 'image_url_profile', $image_url_profile);
	$maybe_update($u_id, 'user_registration_image_url_profile', $image_url_profile);
	$maybe_update($u_id, 'fund_profile_image', $image_url_profile);

	// Optional: also update the related post meta if needed (kept from original code, commented out)
	// update_post_meta($fpid, 'organizations_name', $name_of_school_business);
	// ...

	wp_safe_redirect(add_query_arg('msg', 'success', get_permalink()));
	exit;
}

// Load current values (escape on output)
$f                           = get_user_meta($u_id, 'first_name', true);
$lname                       = get_user_meta($u_id, 'last_name', true);
$org                         = get_user_meta($u_id, 'user_registration_input_company', true);
$sd                          = get_user_meta($u_id, 'user_registration_start_date', true);
$ed                          = get_user_meta($u_id, 'user_registration_end_date', true);
$before                      = get_user_meta($u_id, 'user_registration_radio_1534260773', true);
$hear                        = get_user_meta($u_id, 'user_registration_reference', true);
$referral                    = get_user_meta($u_id, 'user_registration_referral_name', true);
$type                        = get_user_meta($u_id, 'user_registration_favorite_candle_scents', true);
$Participants                = get_user_meta($u_id, 'user_registration_participants_no', true);
$goal_amount                 = get_user_meta($u_id, 'user_registration_fr_goal', true);
$go_toward                   = get_user_meta($u_id, 'user_registration_fund_destination', true);
$selling_goal_val            = get_user_meta($u_id, 'user_registration_selling_goal', true);
$rebate_option               = get_user_meta($u_id, 'user_registration_rebate_option', true);
$payable                     = get_user_meta($u_id, 'user_registration_check_payable', true);
$paypal                      = get_user_meta($u_id, 'user_registration_paypal_email', true);

$name_of_school_business     = get_user_meta($u_id, 'user_registration_name_of_school_business', true);
$user_registration_input_box_phone = get_user_meta($u_id, 'user_registration_input_box_phone', true);
$street_address              = get_user_meta($u_id, 'user_registration_street_address', true);
$City2                       = get_user_meta($u_id, 'user_registration_City2', true);
$select_state                = get_user_meta($u_id, 'user_registration_select_state', true);
$input_box_zip               = get_user_meta($u_id, 'user_registration_input_box_zip', true);
$fundraiser_preference       = get_user_meta($u_id, 'user_registration_fundraiser_preference', true);
$participants_no             = get_user_meta($u_id, 'user_registration_participants_no', true);
$start_date                  = get_user_meta($u_id, 'user_registration_start_date', true);
$end_date                    = get_user_meta($u_id, 'user_registration_end_date', true);
$first_name                  = get_user_meta($u_id, 'first_name', true);
$last_name                   = get_user_meta($u_id, 'last_name', true);
$input_box_1595312762        = get_user_meta($u_id, 'user_registration_input_box_1595312762', true);
$chairpersonaddress          = get_user_meta($u_id, 'user_registration_chairpersonaddress', true);
$City                        = get_user_meta($u_id, 'user_registration_City', true);
$State                       = get_user_meta($u_id, 'user_registration_State', true);
$Zip_Code                    = get_user_meta($u_id, 'user_registration_Zip_Code', true);
$user_email                  = get_user_meta($u_id, 'user_email', true);
$Brochures_will_be_shipped   = get_user_meta($u_id, 'user_registration_Brochures_will_be_shipped', true);
$radio_1534260773            = get_user_meta($u_id, 'user_registration_radio_1534260773', true);
$reference                   = get_user_meta($u_id, 'user_registration_reference', true);
$referral_name               = get_user_meta($u_id, 'user_registration_referral_name', true);
$referral_email              = get_user_meta($u_id, 'user_registration_referral_email', true);
$favorite_candle_scents      = get_user_meta($u_id, 'user_registration_favorite_candle_scents', true);
$fr_goal                     = get_user_meta($u_id, 'user_registration_fr_goal', true);
$selling_goal                = get_user_meta($u_id, 'user_registration_selling_goal', true);
$reason_for_fundraising      = get_user_meta($u_id, 'user_registration_reason_for_fundraising', true);
$input_box_1693293590        = get_user_meta($u_id, 'user_registration_input_box_1693293590', true);
$check_box_new               = get_user_meta($u_id, 'user_registration_check_box_new', true);
$image_url_profile           = get_user_meta($u_id, 'image_url_profile', true);

do_action('et_page_heading');
?>
<style>
.manual-content{background:#f7f7f7}
.manual-content .fundraiser-dashboard-section{width:100%;padding:25px;max-width:100%;margin:0 auto;padding:5px;padding:10px 20px}
.manual-content #email_id{width:84%;display:inline-block}
.manual-content .fundraiser-dashboard-section input{width:100%}
#editInfo{display:inline-block;width:12%;margin:3px 0px;color:#fff;background:#000;text-align:center;padding:4px;font-size:16px;transition:all .5s ease 0s}
.manual-content #AddNewSeller{background:#000;color:#fff;padding:10px 20px;line-height:1.5;text-align:center;word-break:break-word;white-space:normal;border:none;cursor:pointer;-webkit-transition:.35s all ease-out;transition:.35s all ease-out;width:170px;color:#fff;display:inline-block;-webkit-border-radius:0;border:none;background-color:#262626;text-transform:uppercase;border-radius:0;font-size:13px;-webkit-font-smoothing:antialiased;-webkit-transition:background .25s ease-in;-ms-transition:background .25s ease-in;-o-transition:background .25s ease-in;transition:background .25s ease-in}
.manual-content #AddNewSeller:hover{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
#success_message{text-align:left;padding-bottom:10px;font-size:17px;color:green;padding-left:20px}
#imageLoad{display:none}
#flash{color:green}
</style>

<div class="container content-page">
	<div class="page-content sidebar-position-<?php echo isset($l['sidebar']) ? esc_attr($l['sidebar']) : 'without'; ?> sidebar-mobile-<?php echo esc_attr(class_exists('WooCommerce') && (is_checkout() || is_cart() || is_account_page() || in_array('woocommerce-wishlist', get_body_class())) ? ($l['shop-sidebar-mobile'] ?? 'off') : ($l['sidebar-mobile'] ?? 'off')); ?>">
		<div class="row">
			<div class="user-registration ur-frontend-form" id="user-registration-form-37978">
				<form method="post" class="register" novalidate="novalidate">
					<?php wp_nonce_field('edit_fundraiser_info_' . $u_id); ?>
					<div class="ur-form-row">
						<div class="ur-form-grid ur-grid-1" style="width:99%">
							<div data-field-id="name_of_school_business" class="ur-field-item field-text w-50">
								<div class="form-row validate-required" id="name_of_school_business_field">
									<label for="name_of_school_business" class="ur-label">School/Group Name <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="name_of_school_business" id="name_of_school_business" value="<?php echo esc_attr($name_of_school_business); ?>" required maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="input_box_phone" class="ur-field-item field-text w-50">
								<div class="form-row validate-required" id="input_box_phone_field">
									<label for="input_box_phone" class="ur-label">School/Group Phone Number <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="input_box_phone" id="input_box_phone" value="<?php echo esc_attr($user_registration_input_box_phone); ?>" required maxlength="200">
									</span>
								</div>
							</div>

							<div data-field-id="street_address" class="ur-field-item field-text">
								<div class="form-row validate-required" id="street_address_field">
									<label for="street_address" class="ur-label">School/Group Street Address <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="street_address" id="street_address" value="<?php echo esc_attr($street_address); ?>" required maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="City2" class="ur-field-item field-text w-33">
								<div class="form-row" id="City2_field">
									<label for="City2" class="ur-label">City</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="City2" id="City2" value="<?php echo esc_attr($City2); ?>" maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="select_state" class="ur-field-item field-select w-33">
								<div class="form-row validate-required" id="select_state_field">
									<label for="select_state" class="ur-label">State <abbr class="required" title="required">*</abbr></label>
									<select name="select_state" id="select_state" class="select ur-frontend-field" required>
										<option value="Please select..." <?php selected($select_state, 'Please select...'); ?>>Please select...</option>
										<?php
										$states = array(
											'Alabama','Alaska','Arizona','Arkansas','Armed Forces Americas (AA)','Armed Forces Europe (AE)','Armed Forces Pacific (AP)','California','Colorado','Connecticut','Delaware','District of Columbia','Florida','Georgia','Guam','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Marshall Islands','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Palau','Pennsylvania','Puerto Rico','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virgin Islands','Virginia','Washington','West Virginia','Wisconsin','Wyoming','Alberta','British Columbia','Manitoba','New Brunswick','Newfoundland','Northwest Territories','Nova Scotia','Nunavut','Ontario','Prince Edward Island','Quebec','Saskatchewan','Yukon'
										);
										foreach ($states as $st) {
											printf('<option value="%1$s" %2$s>%1$s</option>', esc_attr($st), selected($select_state, $st, false));
										}
										?>
									</select>
								</div>
							</div>

							<div data-field-id="input_box_zip" class="ur-field-item field-text w-33">
								<div class="form-row validate-required" id="input_box_zip_field">
									<label for="input_box_zip" class="ur-label">Zip Code <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="input_box_zip" id="input_box_zip" value="<?php echo esc_attr($input_box_zip); ?>" required maxlength="200">
									</span>
								</div>
							</div>

							<div data-field-id="fundraiser_preference" class="ur-field-item field-radio">
								<div class="form-row validate-required" id="fundraiser_preference_field">
									<label class="ur-label">Select Fundraiser Type <abbr class="required" title="required">*</abbr></label>
									<ul>
										<li class="ur-radio-list firstItem">
											<input type="radio" class="input-radio ur-frontend-field" value="Brochure Sales with an optional Online Fundraising Store (Recommended for higher sales)" name="fundraiser_preference" id="fundraiser_preference_brochure" <?php checked($fundraiser_preference, 'Brochure Sales with an optional Online Fundraising Store (Recommended for higher sales)'); ?> required>
											<label for="fundraiser_preference_brochure" class="radio">Brochure Sales with an optional Online Fundraising Store (Recommended for higher sales)</label>
										</li>
										<li class="ur-radio-list">
											<input type="radio" class="input-radio ur-frontend-field" value="Virtual Fundraiser - No Brochures." name="fundraiser_preference" id="fundraiser_preference_virtual" <?php checked($fundraiser_preference, 'Virtual Fundraiser - No Brochures.'); ?> required>
											<label for="fundraiser_preference_virtual" class="radio">Virtual Fundraiser - No Brochures.</label>
										</li>
										<li class="ur-radio-list lastItem">
											<input type="radio" class="input-radio ur-frontend-field" value="Cashless" name="fundraiser_preference" id="fundraiser_preference_cashless" <?php checked($fundraiser_preference, 'Cashless'); ?> required>
											<label for="fundraiser_preference_cashless" class="radio">Cashless</label>
										</li>
									</ul>
								</div>
							</div>

							<div data-field-id="participants_no" class="ur-field-item field-text w-33">
								<div class="form-row validate-required" id="participants_no_field">
									<label for="participants_no" class="ur-label">Number of Participants <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="participants_no" id="participants_no" value="<?php echo esc_attr($participants_no); ?>" required maxlength="200">
									</span>
								</div>
							</div>

							<div data-field-id="start_date" class="ur-field-item field-date w-33">
								<div class="form-row validate-required" id="start_date_field">
									<label for="start_date" class="ur-label">Date Sale Starts <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input name="start_date" type="date" id="start_date" value="<?php echo esc_attr($start_date); ?>" class="regular-text" required>
									</span>
								</div>
							</div>

							<div data-field-id="end_date" class="ur-field-item field-date w-33">
								<div class="form-row validate-required" id="end_date_field">
									<label for="end_date" class="ur-label">Date Sale Ends <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input name="end_date" type="date" id="end_date" value="<?php echo esc_attr($end_date); ?>" class="regular-text" required>
									</span>
								</div>
							</div>

							<div data-field-id="first_name" class="ur-field-item field-first_name w-33">
								<div class="form-row validate-required" id="first_name_field">
									<label for="first_name" class="ur-label">First Name <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="first_name" id="first_name" value="<?php echo esc_attr($first_name); ?>" required>
									</span>
								</div>
							</div>

							<div data-field-id="last_name" class="ur-field-item field-last_name w-33">
								<div class="form-row validate-required" id="last_name_field">
									<label for="last_name" class="ur-label">Last Name <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="last_name" id="last_name" value="<?php echo esc_attr($last_name); ?>" required>
									</span>
								</div>
							</div>

							<div data-field-id="input_box_1595312762" class="ur-field-item field-text w-33">
								<div class="form-row validate-required" id="input_box_1595312762_field">
									<label for="input_box_1595312762" class="ur-label">Chairperson’s Cell Phone Number <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="input_box_1595312762" id="input_box_1595312762" value="<?php echo esc_attr($input_box_1595312762); ?>" required maxlength="200">
									</span>
								</div>
							</div>

							<div data-field-id="chairpersonaddress" class="ur-field-item field-text">
								<div class="form-row" id="chairpersonaddress_field">
									<label for="chairpersonaddress" class="ur-label">Chairperson’s Street Address</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="chairpersonaddress" id="chairpersonaddress" value="<?php echo esc_attr($chairpersonaddress); ?>" maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="City" class="ur-field-item field-text w-33">
								<div class="form-row" id="City_field">
									<label for="City" class="ur-label">City</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="City" id="City" value="<?php echo esc_attr($City); ?>" maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="State" class="ur-field-item field-text w-33">
								<div class="form-row" id="State_field">
									<label for="State" class="ur-label">State</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="State" id="State" value="<?php echo esc_attr($State); ?>" maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="Zip_Code" class="ur-field-item field-text w-33">
								<div class="form-row" id="Zip_Code_field">
									<label for="Zip_Code" class="ur-label">Zip Code</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="Zip_Code" id="Zip_Code" value="<?php echo esc_attr($Zip_Code); ?>" maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="user_email" class="ur-field-item field-user_email w-50">
								<div class="form-row validate-required user-registration-validated" id="user_email_field">
									<label for="user_email" class="ur-label">Chairperson’s Email Address <abbr class="required" title="required">*</abbr></label>
									<span class="input-wrapper">
										<input type="email" class="input-text input-email ur-frontend-field" name="user_email" id="user_email" value="<?php echo esc_attr($user_email); ?>" required>
									</span>
								</div>
							</div>

							<div data-field-id="Brochures_will_be_shipped" class="ur-field-item field-radio">
								<div class="form-row validate-required" id="Brochures_will_be_shipped_field">
									<label class="ur-label">Brochures will be shipped to School/Group or to the Chairperson? <abbr class="required" title="required">*</abbr></label>
									<ul>
										<li class="ur-radio-list firstItem">
											<input type="radio" class="input-radio ur-frontend-field" value="Ship to School/Group" name="Brochures_will_be_shipped" id="Brochures_will_be_shipped_school" <?php checked($Brochures_will_be_shipped, 'Ship to School/Group'); ?> required>
											<label for="Brochures_will_be_shipped_school" class="radio">Ship to School/Group</label>
										</li>
										<li class="ur-radio-list">
											<input type="radio" class="input-radio ur-frontend-field" value="Ship to Chairperson’s Home" name="Brochures_will_be_shipped" id="Brochures_will_be_shipped_home" <?php checked($Brochures_will_be_shipped, 'Ship to Chairperson’s Home'); ?> required>
											<label for="Brochures_will_be_shipped_home" class="radio">Ship to Chairperson’s Home</label>
										</li>
										<li class="ur-radio-list lastItem">
											<input type="radio" class="input-radio ur-frontend-field" value="No Brochures Needed" name="Brochures_will_be_shipped" id="Brochures_will_be_shipped_none" <?php checked($Brochures_will_be_shipped, 'No Brochures Needed'); ?> required>
											<label for="Brochures_will_be_shipped_none" class="radio">No Brochures Needed</label>
										</li>
									</ul>
								</div>
							</div>

							<div data-field-id="radio_1534260773" class="ur-field-item field-radio">
								<div class="form-row validate-required" id="radio_1534260773_field">
									<label class="ur-label">Have you had a GLOW Fundraiser before? <abbr class="required" title="required">*</abbr></label>
									<ul>
										<li class="ur-radio-list firstItem">
											<input type="radio" class="input-radio ur-frontend-field" value="Yes" name="radio_1534260773" id="radio_1534260773_Yes" <?php checked($radio_1534260773, 'Yes'); ?> required>
											<label for="radio_1534260773_Yes" class="radio">Yes</label>
										</li>
										<li class="ur-radio-list lastItem">
											<input type="radio" class="input-radio ur-frontend-field" value="No" name="radio_1534260773" id="radio_1534260773_No" <?php checked($radio_1534260773, 'No'); ?> required>
											<label for="radio_1534260773_No" class="radio">No</label>
										</li>
									</ul>
								</div>
							</div>

							<div data-field-id="reference" class="ur-field-item field-select">
								<div class="form-row" id="reference_field">
									<label for="reference" class="ur-label">How did you hear about GLOW ?</label>
									<select name="reference" id="reference" class="select ur-frontend-field">
										<?php
										$ref_opts = array('Please select...', 'Fundraising Representative', 'Received an email', 'Online', 'Facebook', 'Referral/Friend', 'Other');
										foreach ($ref_opts as $opt) {
											printf('<option value="%1$s" %2$s>%1$s</option>', esc_attr($opt), selected($reference, $opt, false));
										}
										?>
									</select>
								</div>
							</div>

							<div data-field-id="referral_name" class="ur-field-item field-text">
								<div class="form-row" id="referral_name_field">
									<label for="referral_name" class="ur-label">Name of Referral/Representative/Website</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="referral_name" id="referral_name" value="<?php echo esc_attr($referral_name); ?>" maxlength="200">
									</span>
								</div>
							</div>

							<div data-field-id="referral_email" class="ur-field-item field-email">
								<div class="form-row" id="referral_email_field">
									<label for="referral_email" class="ur-label">Referral/ Your Representative’s Email Address</label>
									<span class="input-wrapper">
										<input type="email" class="input-text input-email ur-frontend-field" name="referral_email" id="referral_email" value="<?php echo esc_attr($referral_email); ?>">
									</span>
								</div>
							</div>

							<div data-field-id="favorite_candle_scents" class="ur-field-item field-select">
								<div class="form-row" id="favorite_candle_scents_field">
									<label for="favorite_candle_scents" class="ur-label">What are your favorite candle scents?</label>
									<select name="favorite_candle_scents" id="favorite_candle_scents" class="select ur-frontend-field">
										<?php
										$scents = array('Please select...', 'Fresh/Clean', 'Fruity/Tropical', 'Aromatherapy');
										foreach ($scents as $s) {
											printf('<option value="%1$s" %2$s>%1$s</option>', esc_attr($s), selected($favorite_candle_scents, $s, false));
										}
										?>
									</select>
								</div>
							</div>

							<div data-field-id="fr_goal" class="ur-field-item field-number">
								<div class="form-row" id="fr_goal_field">
									<label for="fr_goal" class="ur-label">Fundraising Sales Goal (Dollar Amount Earned)</label>
									<span class="input-wrapper">
										<input type="number" class="input-text input-number ur-frontend-field" name="fr_goal" id="fr_goal" value="<?php echo esc_attr($fr_goal); ?>" step="1">
									</span>
								</div>
							</div>

							<div data-field-id="selling_goal" class="ur-field-item field-text">
								<div class="form-row" id="selling_goal_field">
									<label for="selling_goal" class="ur-label">Selling Goal (# of items to be sold per participant)</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="selling_goal" id="selling_goal" value="<?php echo esc_attr($selling_goal); ?>" maxlength="200">
									</span>
								</div>
							</div>

							<div data-field-id="reason_for_fundraising" class="ur-field-item field-textarea">
								<div class="form-row" id="reason_for_fundraising_field">
									<label for="reason_for_fundraising" class="ur-label">Reason for Fundraising (A message to be read by your customers for why you are fundraising/how the funds raised will be used.)</label>
									<textarea name="reason_for_fundraising" class="input-text ur-frontend-field" id="reason_for_fundraising" rows="3"><?php echo esc_textarea($reason_for_fundraising); ?></textarea>
								</div>
							</div>

							<div data-field-id="input_box_1693293590" class="ur-field-item field-text">
								<div class="form-row" id="input_box_1693293590_field">
									<label for="input_box_1693293590" class="ur-label">Profit Check for Online Store will be payable to</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="input_box_1693293590" id="input_box_1693293590" value="<?php echo esc_attr($input_box_1693293590); ?>" maxlength="50">
									</span>
								</div>
							</div>

							<div data-field-id="check_box_new" class="ur-field-item field-checkbox">
								<div class="form-row" id="check_box_new_field">
									<label class="ur-label">checkbox</label>
									<ul>
										<li class="ur-checkbox-list lastItem firstItem">
											<input type="checkbox" class="input-checkbox ur-frontend-field" name="check_box_new[]" value="Yes" <?php checked($check_box_new === 'Yes'); ?>>
											<label class="ur-checkbox-label">Yes! I would like to participate and use GLOW Scented Candles for my stated organization's information above. I understand that I will be shipped all the necessary materials to conduct my fundraiser, and I am ready to get started. Note: We recommend selling for 2-3 weeks.</label>
										</li>
									</ul>
								</div>
							</div>

							<div data-field-id="image_url_profile" class="ur-field-item field-text imgdata" style="display:none;">
								<div class="form-row" id="image_url_profile_field">
									<label for="image_url_profile" class="ur-label">Profile image url</label>
									<span class="input-wrapper">
										<input type="text" class="input-text ur-frontend-field" name="image_url_profile" id="image_url_profile" value="<?php echo esc_attr($image_url_profile); ?>" maxlength="500">
									</span>
								</div>
							</div>

						</div>
					</div>

					<input type="submit" class="" name="submit" value="Save">
				</form>

				<?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
					<div id="flash">Successfully updated!</div>
				<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'invalid_nonce'): ?>
					<div id="flash" style="color:#b00">Invalid request. Please try again.</div>
				<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'forbidden'): ?>
					<div id="flash" style="color:#b00">You are not allowed to edit this user.</div>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div><!-- end row-fluid -->
	</div>
</div>

<script>
jQuery(document).ready(function($){
	setTimeout(function(){
		var selectedImage = '<?php echo esc_js(esc_url($image_url_profile)); ?>';
		if (selectedImage) {
			$('img#img-upload').attr('src', selectedImage);
		}
	}, 500);
});
</script>

<?php
get_footer();