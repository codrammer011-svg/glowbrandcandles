<?php
/**
 * Seller Registration Form with Fundraiser Validation
 * Shortcode: [glow_seller_register_form]
 * Can be embedded directly into Elementor templates, popups, or pages.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$direct_frid = ! empty( $_GET['frid'] ) ? preg_replace( '/\D/', '', sanitize_text_field( $_GET['frid'] ) ) : '';
$is_direct_valid = false;
$direct_org_name = '';

if ( ! empty( $direct_frid ) && strlen( $direct_frid ) === 6 ) {
	$user_info = get_userdata( (int) $direct_frid );
	if ( $user_info && in_array( 'fundraiser', (array) $user_info->roles, true ) ) {
		$is_direct_valid = true;
		$direct_org_name = get_user_meta( $user_info->ID, 'user_registration_name_of_school_business', true );
		if ( empty( $direct_org_name ) ) {
			$direct_org_name = get_user_meta( $user_info->ID, 'user_registration_input_company', true );
		}
		if ( empty( $direct_org_name ) ) {
			$direct_org_name = trim( $user_info->first_name . ' ' . $user_info->last_name );
		}
		if ( empty( $direct_org_name ) ) {
			$direct_org_name = $user_info->display_name;
		}
	}
}

$uid = 'glow_sreg_' . wp_rand( 1000, 9999 );
?>
<div class="glow-seller-register-wrap" id="<?php echo esc_attr( $uid ); ?>">
	<style>
	#<?php echo esc_attr( $uid ); ?> {
		width: 100%;
		max-width: 780px;
		margin: 0 auto;
		font-family: inherit;
		box-sizing: border-box;
	}
	#<?php echo esc_attr( $uid ); ?> * {
		box-sizing: border-box;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-wizard {
		margin: 15px auto 30px;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-steps-bar {
		display: flex;
		justify-content: space-between;
		position: relative;
		margin-bottom: 25px;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-steps-bar::before {
		content: '';
		position: absolute;
		top: 18px;
		left: 10%;
		right: 10%;
		height: 2px;
		background: #e2e8f0;
		z-index: 0;
	}
	:root {
		--glow-sr-primary: var(--e-global-color-primary, #E91E63);
		--glow-sr-secondary: var(--e-global-color-secondary, #FFD700);
		--glow-sr-accent: var(--e-global-color-accent, #5a6a80);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-step-item {
		position: relative;
		z-index: 1;
		text-align: center;
		flex: 1;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-step-bubble {
		width: 38px;
		height: 38px;
		line-height: 36px;
		border-radius: 50%;
		background: #ffffff;
		border: 2px solid #cbd5e1;
		color: #64748b;
		display: inline-block;
		font-weight: 700;
		font-size: 14px;
		transition: all 0.25s ease;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-step-item.active .glow-sr-step-bubble {
		background: var(--e-global-color-primary, #E91E63);
		border-color: var(--e-global-color-primary, #E91E63);
		color: #ffffff;
		box-shadow: 0 0 0 4px rgba(233, 30, 99, 0.15);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-step-item.completed .glow-sr-step-bubble {
		background: #16a34a;
		border-color: #16a34a;
		color: #ffffff;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-step-title {
		font-size: 13px;
		font-weight: 600;
		color: #475569;
		margin-top: 8px;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-card {
		background: #ffffff;
		border-radius: 14px;
		padding: 30px;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
		border: 1px solid #f1f5f9;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-alert {
		display: none;
		padding: 12px 16px;
		border-radius: 8px;
		font-size: 14px;
		margin-bottom: 20px;
		text-align: left;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-alert-info {
		background: #eff6ff;
		border: 1px solid #bfdbfe;
		color: #1d4ed8;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-alert-error {
		background: #fef2f2;
		border: 1px solid #fecaca;
		color: #dc2626;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-alert-success {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
		color: #15803d;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-form-group {
		margin-bottom: 20px;
		text-align: left;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-form-group label {
		display: block;
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin-bottom: 8px;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-input {
		width: 100%;
		height: 48px;
		padding: 0 16px;
		border: 1.5px solid #cbd5e1;
		border-radius: 8px;
		font-size: 15px;
		color: #1e293b;
		outline: none;
		transition: border-color 0.2s, box-shadow 0.2s;
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-input:focus {
		border-color: var(--e-global-color-primary, #E91E63);
		box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.15);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: var(--e-global-color-primary, #E91E63);
		color: #ffffff;
		font-size: 15px;
		font-weight: 700;
		border: none;
		border-radius: 8px;
		padding: 13px 32px;
		cursor: pointer;
		transition: all 0.25s ease;
		text-decoration: none;
		box-shadow: 0 4px 14px rgba(233, 30, 99, 0.25);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-btn:hover {
		background: var(--e-global-color-primary, #E91E63);
		filter: brightness(0.9);
		color: #ffffff;
		box-shadow: 0 6px 18px rgba(233, 30, 99, 0.35);
		transform: translateY(-1px);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-btn:active {
		transform: translateY(0);
		box-shadow: 0 2px 8px rgba(233, 30, 99, 0.25);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-btn.glow-sr-btn-back {
		background: var(--e-global-color-accent, #5a6a80) !important;
		box-shadow: 0 4px 12px rgba(90, 106, 128, 0.2);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-btn.glow-sr-btn-back:hover {
		background: var(--e-global-color-accent, #5a6a80) !important;
		filter: brightness(0.88);
		box-shadow: 0 6px 16px rgba(90, 106, 128, 0.3);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-btn:disabled {
		opacity: 0.5;
		cursor: not-allowed;
		transform: none !important;
		box-shadow: none !important;
	}
	#<?php echo esc_attr( $uid ); ?> button[type="submit"].ur-submit-button,
	#<?php echo esc_attr( $uid ); ?> .user-registration-Button,
	#<?php echo esc_attr( $uid ); ?> .ur-frontend-form button[type="submit"] {
		background: var(--e-global-color-primary, #E91E63) !important;
		color: #ffffff !important;
		border-radius: 8px !important;
		font-weight: 700 !important;
		padding: 13px 32px !important;
		border: none !important;
		cursor: pointer !important;
		transition: all 0.25s ease !important;
		box-shadow: 0 4px 14px rgba(233, 30, 99, 0.25) !important;
	}
	#<?php echo esc_attr( $uid ); ?> button[type="submit"].ur-submit-button:hover,
	#<?php echo esc_attr( $uid ); ?> .user-registration-Button:hover,
	#<?php echo esc_attr( $uid ); ?> .ur-frontend-form button[type="submit"]:hover {
		background: var(--e-global-color-primary, #E91E63) !important;
		filter: brightness(0.9) !important;
		box-shadow: 0 6px 18px rgba(233, 30, 99, 0.35) !important;
		transform: translateY(-1px);
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-spinner {
		display: none;
		width: 18px;
		height: 18px;
		border: 2px solid #ffffff;
		border-top-color: transparent;
		border-radius: 50%;
		animation: glowSpin 0.7s linear infinite;
		margin-right: 8px;
	}
	@keyframes glowSpin {
		to { transform: rotate(360deg); }
	}
	#<?php echo esc_attr( $uid ); ?> .glow-sr-badge {
		display: inline-block;
		background: #fff0f5;
		color: var(--e-global-color-primary, #E91E63);
		border: 1px solid rgba(233, 30, 99, 0.2);
		padding: 8px 14px;
		border-radius: 6px;
		font-size: 14px;
		font-weight: 600;
		margin-bottom: 20px;
	}
	</style>

	<?php if ( $is_direct_valid ) : ?>
		<!-- DIRECT MODE (?frid=XXXXXX) -->
		<div class="glow-sr-card">
			<div class="glow-sr-badge">
				<strong>Fundraiser:</strong> #<?php echo esc_html( $direct_frid ); ?> 
				<?php if ( ! empty( $direct_org_name ) ) : ?>
					&middot; <?php echo esc_html( $direct_org_name ); ?>
				<?php endif; ?>
			</div>
			<?php echo do_shortcode( '[user_registration_form id="62307"]' ); ?>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			var directFid = '<?php echo esc_js( $direct_frid ); ?>';
			if (directFid) {
				var $root = $('#<?php echo esc_attr( $uid ); ?>');
				var setDirectFid = function() {
					var fidEl = $root.find('input[name="seller_fundraiser"], #seller_fundraiser, input.seller_fundraiser_input, .seller_fundraiser_input input, input[data-field-id="seller_fundraiser"], [data-field-id="seller_fundraiser"] input');
					if (fidEl.length) {
						fidEl.val(directFid).trigger('input').trigger('change').prop('readonly', true).attr('readonly', 'readonly');
					}
				};
				setDirectFid();
				setTimeout(setDirectFid, 200);
				setTimeout(setDirectFid, 800);
			}
		});
		</script>
	<?php else : ?>
		<!-- STEPPED WIZARD MODE -->
		<div class="glow-sr-wizard">
			<div class="glow-sr-steps-bar">
				<div class="glow-sr-step-item active glow-sr-nav-1">
					<span class="glow-sr-step-bubble">1</span>
					<div class="glow-sr-step-title">Verify Email</div>
				</div>
				<div class="glow-sr-step-item glow-sr-nav-2">
					<span class="glow-sr-step-bubble">2</span>
					<div class="glow-sr-step-title">Fundraiser Code</div>
				</div>
				<div class="glow-sr-step-item glow-sr-nav-3">
					<span class="glow-sr-step-bubble">3</span>
					<div class="glow-sr-step-title">Register</div>
				</div>
			</div>

			<div class="glow-sr-card">
				<div class="glow-sr-alert" role="alert"></div>

				<!-- STEP 1: Validate Email -->
				<div class="glow-sr-step-content glow-sr-content-1">
					<div class="glow-sr-form-group">
						<label for="<?php echo esc_attr( $uid ); ?>_email">Your Email Address</label>
						<input type="email" id="<?php echo esc_attr( $uid ); ?>_email" class="glow-sr-input" placeholder="Enter your email address" autocomplete="email" required />
					</div>
					<div style="text-align: right;">
						<button type="button" class="glow-sr-btn glow-sr-btn-validate-email">
							<span class="glow-sr-spinner"></span>
							<span>Continue &rarr;</span>
						</button>
					</div>
				</div>

				<!-- STEP 2: Validate Fundraiser ID -->
				<div class="glow-sr-step-content glow-sr-content-2" style="display:none;">
					<div class="glow-sr-form-group">
						<label for="<?php echo esc_attr( $uid ); ?>_fid">6-Digit Fundraiser ID</label>
						<input type="number" id="<?php echo esc_attr( $uid ); ?>_fid" class="glow-sr-input" placeholder="e.g. 213971" maxlength="6" inputmode="numeric" required />
					</div>
					<div style="display: flex; justify-content: space-between;">
						<button type="button" class="glow-sr-btn glow-sr-btn-back">
							&larr; Back
						</button>
						<button type="button" class="glow-sr-btn glow-sr-btn-validate-fid">
							<span class="glow-sr-spinner"></span>
							<span>Validate & Continue &rarr;</span>
						</button>
					</div>
				</div>

				<!-- STEP 3: Complete Registration Form -->
				<div class="glow-sr-step-content glow-sr-content-3" style="display:none;">
					<div class="glow-sr-badge glow-sr-summary-badge" style="display:none;"></div>
					<?php echo do_shortcode( '[user_registration_form id="62307"]' ); ?>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		(function($) {
			'use strict';
			var root = $('#<?php echo esc_attr( $uid ); ?>');
			var ajaxUrl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';

			var step1 = root.find('.glow-sr-content-1');
			var step2 = root.find('.glow-sr-content-2');
			var step3 = root.find('.glow-sr-content-3');

			var nav1 = root.find('.glow-sr-nav-1');
			var nav2 = root.find('.glow-sr-nav-2');
			var nav3 = root.find('.glow-sr-nav-3');

			var alertBox = root.find('.glow-sr-alert');
			var emailInput = root.find('#<?php echo esc_attr( $uid ); ?>_email');
			var fidInput = root.find('#<?php echo esc_attr( $uid ); ?>_fid');

			var validatedEmail = '';
			var validatedFid = '';

			function showAlert(msg, type) {
				alertBox.removeClass('glow-sr-alert-info glow-sr-alert-error glow-sr-alert-success')
					.addClass('glow-sr-alert-' + type)
					.html(msg)
					.show();
			}

			function hideAlert() {
				alertBox.hide().empty();
			}

			function isValidEmail(email) {
				return /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email);
			}

			// Validate Email (Step 1)
			root.find('.glow-sr-btn-validate-email').on('click', function(e) {
				e.preventDefault();
				hideAlert();
				var email = emailInput.val().trim();

				if (!email) {
					showAlert('Please enter your email address.', 'error');
					emailInput.focus();
					return;
				}
				if (!isValidEmail(email)) {
					showAlert('Please enter a valid email address.', 'error');
					emailInput.focus();
					return;
				}

				var btn = $(this);
				var spinner = btn.find('.glow-sr-spinner');
				btn.prop('disabled', true);
				spinner.show();

				$.ajax({
					type: 'POST',
					url: ajaxUrl,
					data: {
						action: 'ajax_seller_register_email_validation',
						seller_email: email
					},
					success: function(response) {
						spinner.hide();
						btn.prop('disabled', false);

						if (response && response.data && response.data.action == 1) {
							validatedEmail = email;
							nav1.removeClass('active').addClass('completed');
							nav2.addClass('active');
							step1.hide();
							step2.show();
							fidInput.focus();
						} else if (response && response.data && response.data.action == 2) {
							if (response.data.user_type == 1) {
								showAlert('This email has already been used for fundraising. Please use a different email.', 'error');
							} else if (response.data.user_type == 2) {
								showAlert('Email already exists. Redirecting to seller rebooking...', 'error');
								setTimeout(function() {
									window.location.href = '<?php echo esc_url( home_url( '/seller-rebooking/' ) ); ?>';
								}, 2000);
							} else {
								showAlert('Email already exists. Please use a different email or rebook.', 'error');
							}
						} else {
							showAlert('Validation failed. Please try again.', 'error');
						}
					},
					error: function() {
						spinner.hide();
						btn.prop('disabled', false);
						showAlert('Unable to validate email. Please check your connection and try again.', 'error');
					}
				});
			});

			// Back button in Step 2
			root.find('.glow-sr-btn-back').on('click', function(e) {
				e.preventDefault();
				hideAlert();
				nav2.removeClass('active');
				nav1.removeClass('completed').addClass('active');
				step2.hide();
				step1.show();
				emailInput.focus();
			});

			// Validate Fundraiser ID (Step 2)
			root.find('.glow-sr-btn-validate-fid').on('click', function(e) {
				e.preventDefault();
				hideAlert();
				var fid = fidInput.val().trim().replace(/\D/g, '');

				if (!fid || fid.length !== 6) {
					showAlert('Please enter a valid 6-digit Fundraiser ID.', 'error');
					fidInput.focus();
					return;
				}

				var btn = $(this);
				var spinner = btn.find('.glow-sr-spinner');
				btn.prop('disabled', true);
				spinner.show();

				$.ajax({
					type: 'POST',
					url: ajaxUrl,
					data: {
						action: 'ajax_seller_register_FID_validation',
						fund_id: fid
					},
					success: function(response) {
						spinner.hide();
						btn.prop('disabled', false);

						if (response && response.data && response.data.action == 1) {
							validatedFid = fid;
							showAlert('Fundraiser verified! Loading registration form...', 'success');

							setTimeout(function() {
								hideAlert();
								nav2.removeClass('active').addClass('completed');
								nav3.addClass('active');
								step2.hide();
								step3.show();

								// Auto-fill and lock verified fields across all field name / ID variations
								var populateVerifiedFields = function() {
									// Email field
									var emailEl = step3.find('input[name="user_email"], #user_email, input[data-field-id="user_email"], input[type="email"]');
									if (emailEl.length) {
										emailEl.val(validatedEmail).trigger('input').trigger('change').prop('readonly', true).attr('readonly', 'readonly');
									}

									// Fundraiser ID field
									var fidEl = step3.find('input[name="seller_fundraiser"], #seller_fundraiser, input.seller_fundraiser_input, .seller_fundraiser_input input, input[data-field-id="seller_fundraiser"], [data-field-id="seller_fundraiser"] input');
									if (fidEl.length) {
										fidEl.val(validatedFid).trigger('input').trigger('change').prop('readonly', true).attr('readonly', 'readonly');
									}
								};

								populateVerifiedFields();
								setTimeout(populateVerifiedFields, 100);
								setTimeout(populateVerifiedFields, 400);
								setTimeout(populateVerifiedFields, 1000);

								// Summary badge
								root.find('.glow-sr-summary-badge')
									.html('<strong>Fundraiser ID:</strong> #' + validatedFid + ' &nbsp;|&nbsp; <strong>Seller Email:</strong> ' + validatedEmail)
									.show();
							}, 600);
						} else if (response && response.data && response.data.action == 2) {
							showAlert('Fundraiser ID not found. Please double-check the ID.', 'error');
						} else if (response && response.data && response.data.action == 4) {
							showAlert('This fundraiser is currently inactive.', 'error');
						} else {
							showAlert('Invalid Fundraiser ID. Please check and try again.', 'error');
						}
					},
					error: function() {
						spinner.hide();
						btn.prop('disabled', false);
						showAlert('Unable to validate Fundraiser ID. Please try again.', 'error');
					}
				});
			});
		})(jQuery);
		</script>
	<?php endif; ?>
	<script type="text/javascript">
	/* Phone field: start on United States instead of the visitor's IP country. */
	(function ($) {
		function forceUs() {
			var original = $.fn.intlTelInput;
			if (!original || original.glowForceUs) return !!original;
			$.fn.intlTelInput = function (options) {
				if (options && typeof options === 'object') {
					options = $.extend({}, options, { initialCountry: 'us' });
				}
				return original.apply(this, [options].concat([].slice.call(arguments, 1)));
			};
			$.extend($.fn.intlTelInput, original, { glowForceUs: true });
			return true;
		}
		if (!forceUs()) $(forceUs);
		// Fields set up before the override existed.
		$(window).on('load', function () {
			$('.ur-smart-phone-field').each(function () {
				var $el = $(this);
				if (!$el.val() && $el.data('plugin_intlTelInput')) $el.intlTelInput('setCountry', 'us');
			});
		});
	})(jQuery);
	</script>
</div>
