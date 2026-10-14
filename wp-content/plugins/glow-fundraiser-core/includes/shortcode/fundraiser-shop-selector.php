<?php
/**
 * Shortcode: [glow_fundraiser_shop]
 *
 * Redesigned popup-ready fundraiser shop entry form.
 *
 * FLOW:
 *   (Fundraiser ID and Seller Name fields are shown side by side together.)
 *   Step 1 – Customer types a 6-digit Fundraiser ID (auto-validates on 6 digits).
 *   Step 2 – Seller section appears:
 *            • Registered sellers exist  → dropdown (with "Other / Add Seller").
 *            • No registered sellers     → free-text name input.
 *   "Start Shopping" saves the WC session and redirects.
 *   "Shop Glow" skips the fundraiser flow entirely.
 *
 * AUTO-POPUP (Elementor):
 *   A wp_footer hook fires on every WooCommerce shop page. If no fundraiser
 *   session exists it triggers the Elementor popup whose ID is stored in the
 *   WP option `glow_fundraiser_popup_id`.
 *   Set that option by adding  popup_id="123"  to the shortcode once.
 *
 * Reuses existing AJAX handlers:
 *   wp_fundraiser_id_validitor  – validates ID, returns org name
 *   seller_drop_down_list       – returns <option> tags for that fundraiser
 *   save_fundraiser_session     – writes fundraiser + seller to WC session
 *
 * Shortcode attributes:
 *   title           – Modal heading   (default: "Ready to Shop & Support?")
 *   subtitle        – Sub-text beneath heading
 *   button          – Primary CTA label          (default: "Start Shopping")
 *   shop_url        – Redirect after session save (default: WC shop page)
 *   shop_glow_url   – Fallback "Shop Glow" URL   (defaults to shop_url)
 *   shop_glow_label – Fallback button label       (default: "Shop Glow")
 *   popup_id        – Elementor popup ID; saved to DB so needed only once
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─── 1. AUTO-POPUP TRIGGER ───────────────────────────────────────────────────
// Fires in wp_footer on the WooCommerce shop page.
// Suppressed when a fundraiser is already stored in the WC session.
add_action( 'wp_footer', 'glow_fc_auto_popup_trigger', 30 );
add_action( 'wp_footer', 'glow_fc_continue_shopping_choice', 20 );

function glow_fc_continue_shopping_choice() {
    $popup_id = (int) get_option('glow_fundraiser_popup_id', 61511);
    ?>
    <script>
    (function () {
        window.glowShopPopupDismissed = function () {
            if (/(?:^|;\s*)glow_shop_continue=1(?:;|$)/.test(document.cookie)) return true;
            try { return sessionStorage.getItem('glow_shop_continue') === '1'; } catch (error) { return false; }
        };
        document.addEventListener('click', function (event) {
            var button = event.target && event.target.closest ? event.target.closest('.continue_shopping') : null;
            if (!button) return;
            // Save before the link navigates, including buttons inside dynamically loaded popups.
            document.cookie = 'glow_shop_continue=1;max-age=2592000;path=/;SameSite=Lax' + (location.protocol === 'https:' ? ';Secure' : '');
            try { sessionStorage.setItem('glow_shop_continue', '1'); } catch (error) {}
            try {
                if (window.elementorProFrontend && elementorProFrontend.modules.popup) {
                    elementorProFrontend.modules.popup.closePopup({id: <?php echo $popup_id; ?>}, {currentTarget: button});
                }
            } catch (error) {}
        }, true);
    })();
    </script>
    <?php
}


function glow_fc_is_popup_shop_page() {
	$path = wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH );
	$shop_path = wp_parse_url( home_url( '/shop/' ), PHP_URL_PATH );
	return untrailingslashit( (string) $path ) === untrailingslashit( $shop_path );
}



function glow_fc_auto_popup_trigger() {
	if ( ! glow_fc_is_popup_shop_page() ) {
		return;
	}

	$has_session = false;
	if ( function_exists( 'WC' ) && WC()->session ) {
		$fr_id = WC()->session->get( 'fridID' );
		if ( empty( $fr_id ) ) {
			$fr_id = WC()->session->get( 'popup_fundraiser_id' );
		}
		if ( ! empty( $fr_id ) ) {
			$has_session = true;
		}
	}

	$has_session = $has_session || ! empty( $_SESSION['fridID'] ) || ! empty( $_COOKIE['fundraiserID'] );
	$popup_id = (int) get_option( 'glow_fundraiser_popup_id', 61511 );

	?>
	<script>
	/* Glow Fundraiser – auto-popup trigger */
	(function () {
		var hasSession = <?php echo $has_session ? 'true' : 'false'; ?>;
		var popupId    = <?php echo $popup_id; ?>;

		// A retail choice suppresses auto-opening only; native manual triggers remain available.
		if ( hasSession || ! popupId || /(?:^|;\s*)fundraiserID=([^;]+)/.test(document.cookie) || window.glowShopPopupDismissed() ) { return; }

		function tryOpen() {
            if (window.glowShopPopupDismissed()) return true;
			if (window.elementorProFrontend && elementorProFrontend.modules && elementorProFrontend.modules.popup) {
                try {
                    var documents = window.elementorFrontend && elementorFrontend.documentsManager;
                    if (!documents || !documents.documents[popupId]) return false;
                    elementorProFrontend.modules.popup.showPopup({ id: popupId });
                    return true;
                } catch (error) { return false; }
            }
			return false;
		}

		if ( ! tryOpen() ) {
			var attempts = 0;
			var iv = setInterval( function () {
				attempts++;
				if ( tryOpen() || attempts >= 100 ) { clearInterval( iv ); }
			}, 150 );
		}
	})();
	</script>
	<?php
}

// ─── 2. AJAX: SESSION STATUS ─────────────────────────────────────────────────
add_action( 'wp_ajax_glow_check_fundraiser_session',        'glow_fc_check_session_ajax' );
add_action( 'wp_ajax_nopriv_glow_check_fundraiser_session', 'glow_fc_check_session_ajax' );

function glow_fc_check_session_ajax() {
	$has = false;
	if ( function_exists( 'WC' ) && WC()->session ) {
		$fr_id = WC()->session->get( 'fridID' );
		if ( empty( $fr_id ) ) {
			$fr_id = WC()->session->get( 'popup_fundraiser_id' );
		}
		$has = ! empty( $fr_id );
	}
	wp_send_json_success( array( 'has_session' => $has ) );
}

// ─── 3. AJAX: FUNDRAISER VALIDATION ──────────────────────────────────────────
add_action( 'wp_ajax_glow_validate_fundraiser_id',        'glow_fc_validate_fundraiser_id' );
add_action( 'wp_ajax_nopriv_glow_validate_fundraiser_id', 'glow_fc_validate_fundraiser_id' );

function glow_fc_validate_fundraiser_id() {
	$fid = isset( $_POST['fund_id'] ) ? sanitize_text_field( $_POST['fund_id'] ) : '';
	$fid = preg_replace( '/\D/', '', $fid );

	if ( strlen( $fid ) !== 6 ) {
		wp_send_json_error( array( 'message' => 'Please enter a valid 6-digit Fundraiser ID.' ) );
	}

	// 1. Check if user exists with role 'fundraiser'
	$user = get_userdata( (int) $fid );
	if ( $user && in_array( 'fundraiser', (array) $user->roles, true ) ) {
		$status = get_user_meta( $user->ID, 'user_action_status', true );
		if ( (string) $status === '2' ) {
			wp_send_json_error( array( 'message' => 'This fundraiser is currently inactive.' ) );
		}

		$org = get_user_meta( $user->ID, 'user_registration_name_of_school_business', true );
		if ( empty( $org ) ) {
			$org = get_user_meta( $user->ID, 'user_registration_input_company', true );
		}
		if ( empty( $org ) ) {
			$org = trim( $user->first_name . ' ' . $user->last_name );
		}
		if ( empty( $org ) ) {
			$org = $user->display_name;
		}

		wp_send_json_success( array(
			'org_name' => $org,
			'fund_id'  => $user->ID,
		) );
	}

	// 2. Fallback: Check custom post type 'fundraiser' (legacy)
	$posts = get_posts( array(
		'post_type'   => 'fundraiser',
		'meta_key'    => 'fundraiser_id',
		'meta_value'  => $fid,
		'numberposts' => 1,
	) );
	if ( ! empty( $posts ) ) {
		$post_id = $posts[0]->ID;
		$org     = function_exists( 'get_field' ) ? get_field( 'organizations_name', $post_id ) : '';
		if ( empty( $org ) ) {
			$org = $posts[0]->post_title;
		}
		wp_send_json_success( array(
			'org_name' => $org,
			'fund_id'  => $fid,
		) );
	}

	wp_send_json_error( array( 'message' => 'Fundraiser ID not found. Please double-check and try again.' ) );
}

/**
 * AJAX: return <option> tags of sellers registered under a fundraiser code.
 *
 * The shortcode JS calls action 'seller_drop_down_list' with request_id = the
 * 6-digit fundraiser ID after validation, and parses the returned HTML options.
 * This handler was missing (only the old theme had it), so entering a fundraiser
 * ID never populated the seller dropdown. Sellers are linked to a fundraiser via
 * the user meta `user_registration_seller_fundraiser` = the fundraiser's ID.
 */
if ( ! function_exists( 'seller_drop_down_list' ) ) {
	add_action( 'wp_ajax_seller_drop_down_list',        'glow_fc_seller_drop_down_list' );
	add_action( 'wp_ajax_nopriv_seller_drop_down_list', 'glow_fc_seller_drop_down_list' );
	function glow_fc_seller_drop_down_list() {
		$fid = isset( $_POST['request_id'] ) ? preg_replace( '/\D/', '', (string) $_POST['request_id'] ) : '';

		// Always emit the placeholder the JS expects (it skips "No-seller").
		echo '<option value="No-seller" selected="selected">No Seller</option>';

		if ( strlen( $fid ) !== 6 ) {
			wp_die();
		}

		$user = get_userdata( (int) $fid );
		if ( ! $user || ! in_array( 'fundraiser', (array) $user->roles, true ) ) {
			wp_die();
		}

		// Sellers who registered under this fundraiser's code.
		$sellers = get_users( array(
			'meta_key'   => 'user_registration_seller_fundraiser',
			'meta_value' => $fid,
			'orderby'    => 'display_name',
			'order'      => 'ASC',
		) );

		foreach ( $sellers as $s ) {
			$first = get_user_meta( $s->ID, 'first_name', true );
			$last  = get_user_meta( $s->ID, 'last_name', true );
			$name  = trim( $first . ' ' . $last );
			if ( '' === $name ) {
				$name = $s->display_name;
			}
			if ( '' === $name ) {
				continue;
			}
			printf(
				'<option frdata="%s" value="%d">%s</option>',
				esc_attr( $fid ),
				(int) $s->ID,
				esc_html( $name )
			);
		}

		// "Add Seller" custom entry. On checkout, selecting value="custom" reveals the
		// "Enter Seller Name" text field (#wc_seller_field_custom_field). The shop-selector
		// shortcode skips this option (it appends its own), so there is no duplicate there.
		echo '<option frdata="custom" value="custom" class="add-seller">Add Seller</option>';

		wp_die();
	}
}

// Ensure session stores fundraiser & seller across native PHP session, WC session, and cookies
add_action( 'wp_ajax_save_fundraiser_session', 'glow_fc_save_session_sync', 1 );
add_action( 'wp_ajax_nopriv_save_fundraiser_session', 'glow_fc_save_session_sync', 1 );

function glow_fc_save_session_sync() {
	$fid    = ! empty( $_POST['popup_fundraiser_id'] ) ? sanitize_text_field( $_POST['popup_fundraiser_id'] ) : '';
	$seller = ! empty( $_POST['popup_fundraiser_name'] ) ? sanitize_text_field( $_POST['popup_fundraiser_name'] ) : '';

	// Fall back to the fundraiser already stored in session/cookie, so the catalog
	// "Add order to cart" flow can update just the seller (it may not resend the id).
	if ( empty( $fid ) && function_exists( 'WC' ) && WC()->session ) {
		$fid = WC()->session->get( 'fridID' );
		if ( empty( $fid ) ) {
			$fid = WC()->session->get( 'popup_fundraiser_id' );
		}
	}
	if ( empty( $fid ) && ! empty( $_COOKIE['fundraiserID'] ) ) {
		$fid = sanitize_text_field( $_COOKIE['fundraiserID'] );
	}

	if ( ! empty( $fid ) ) {
		// 1. WooCommerce Session
		if ( function_exists( 'WC' ) && WC()->session ) {
			WC()->session->set( 'fridID', $fid );
			WC()->session->set( 'popup_fundraiser_id', $fid );
			if ( ! empty( $seller ) ) {
				WC()->session->set( 'seller_name', $seller );
			}
		}

		// 2. Native PHP Session
		if ( session_status() === PHP_SESSION_NONE && ! headers_sent() ) {
			session_start();
		}
		if ( isset( $_SESSION ) ) {
			$_SESSION['fridID']      = $fid;
			$_SESSION['seller_name'] = $seller;
		}

		// 3. Fallback Cookies (30 days)
		if ( ! headers_sent() ) {
			setcookie( 'fundraiserID', $fid, time() + 30 * DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN );
			if ( ! empty( $seller ) ) {
				setcookie( 'seller_name', $seller, time() + 30 * DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN );
			}
		}
	}
}

// Prefill checkout fields from session
add_filter( 'woocommerce_checkout_get_value', 'glow_fc_checkout_prefill_fundraiser_fields', 20, 2 );
function glow_fc_checkout_prefill_fundraiser_fields( $value, $input ) {
	if ( ! empty( $value ) ) {
		return $value;
	}

	$fid = '';
	$seller = '';

	if ( function_exists( 'WC' ) && WC()->session ) {
		$fid = WC()->session->get( 'fridID' );
		if ( empty( $fid ) ) {
			$fid = WC()->session->get( 'popup_fundraiser_id' );
		}
		$seller = WC()->session->get( 'seller_name' );
	}

	if ( empty( $fid ) && session_status() === PHP_SESSION_ACTIVE && isset( $_SESSION['fridID'] ) ) {
		$fid = $_SESSION['fridID'];
	}
	if ( empty( $seller ) && session_status() === PHP_SESSION_ACTIVE && isset( $_SESSION['seller_name'] ) ) {
		$seller = $_SESSION['seller_name'];
	}
	if ( empty( $fid ) && isset( $_COOKIE['fundraiserID'] ) ) {
		$fid = sanitize_text_field( $_COOKIE['fundraiserID'] );
	}
	if ( empty( $seller ) && isset( $_COOKIE['seller_name'] ) ) {
		$seller = sanitize_text_field( $_COOKIE['seller_name'] );
	}

	if ( empty( $fid ) ) {
		return $value;
	}

	if ( 'wc_checkbox_field' === $input ) {
		return 'Yes';
	}
	if ( 'fundraiser_field' === $input ) {
		return $fid;
	}

	return $value;
}

// Ensure JS on checkout populates & reveals the fields immediately
add_action( 'woocommerce_after_checkout_form', 'glow_fc_checkout_auto_populate_js' );
function glow_fc_checkout_auto_populate_js() {
	$fid = '';
	$seller = '';

	if ( function_exists( 'WC' ) && WC()->session ) {
		$fid = WC()->session->get( 'fridID' );
		if ( empty( $fid ) ) {
			$fid = WC()->session->get( 'popup_fundraiser_id' );
		}
		$seller = WC()->session->get( 'seller_name' );
	}
	if ( empty( $fid ) && session_status() === PHP_SESSION_ACTIVE && isset( $_SESSION['fridID'] ) ) {
		$fid = $_SESSION['fridID'];
	}
	if ( empty( $seller ) && session_status() === PHP_SESSION_ACTIVE && isset( $_SESSION['seller_name'] ) ) {
		$seller = $_SESSION['seller_name'];
	}
	if ( empty( $fid ) && isset( $_COOKIE['fundraiserID'] ) ) {
		$fid = sanitize_text_field( $_COOKIE['fundraiserID'] );
	}
	if ( empty( $seller ) && isset( $_COOKIE['seller_name'] ) ) {
		$seller = sanitize_text_field( $_COOKIE['seller_name'] );
	}

	if ( empty( $fid ) ) {
		return;
	}
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var fid = <?php echo wp_json_encode( (string) $fid ); ?>;
		var seller = <?php echo wp_json_encode( (string) $seller ); ?>;

		// Select 'Yes'
		$('input[name="wc_checkbox_field"][value="Yes"]').prop('checked', true);

		// Set Fundraiser Code & show containers
		$('#fundraiser_field').val(fid);
		$('#fundraiser_field_field').show();
		$('#wc_seller_field_field').show();

		// Match seller in select dropdown; hide custom input if found in dropdown
		function applySeller() {
			if ( ! seller ) return;
			var cleanTarget = seller.trim().toLowerCase();
			var matched = false;

			$('select#wc_seller_field option').each(function() {
				var optText = $(this).text().trim().toLowerCase();
				var optVal  = $(this).val();

				// Match by name or option text
				if ( optVal !== '' && optVal !== 'No-seller' && optVal !== 'custom' && optText === cleanTarget ) {
					$(this).prop('selected', true);
					$('select#wc_seller_field').val(optVal);
					matched = true;
					return false;
				}
			});

			if ( matched ) {
				// Hide and clear custom seller field because seller exists in dropdown
				$('#wc_seller_field_custom').val('');
				$('#wc_seller_field_custom_field').hide();
			} else {
				// Only if dropdown has loaded and no match
				var optionCount = $('select#wc_seller_field option').length;
				if ( optionCount > 2 ) {
					$('select#wc_seller_field').val('custom');
					$('#wc_seller_field_custom').val(seller);
					$('#wc_seller_field_custom_field').show();
				}
			}
		}

		applySeller();
		setTimeout(applySeller, 500);
		setTimeout(applySeller, 1200);
		setTimeout(applySeller, 2500);
	});
	</script>
	<?php
}

// ─── 4. SHORTCODE ────────────────────────────────────────────────────────────
add_shortcode( 'glow_fundraiser_shop', 'glow_fc_fundraiser_shop_shortcode' );

function glow_fc_fundraiser_shop_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'title'           => '',
			'subtitle'        => '',
			'button'          => 'Start Shopping',
			'shop_url'        => '',
			'shop_glow_url'   => '',
			'shop_glow_label' => 'Shop Glow',
			'popup_id'        => '',
			'show_icon'       => 'false',
			'show_header'     => 'false',
			'show_glow'       => 'false',
			'show_gratitude'  => 'false',
			'layout'          => 'minimal', // 'card' or 'minimal'
		),
		$atts,
		'glow_fundraiser_shop'
	);

	// Persist popup_id so the footer trigger picks it up automatically.
	if ( ! empty( $atts['popup_id'] ) ) {
		update_option( 'glow_fundraiser_popup_id', absint( $atts['popup_id'] ), false );
	}

	$shop_url = $atts['shop_url'];
	if ( '' === $shop_url ) {
		$shop_url = function_exists( 'wc_get_page_permalink' )
			? wc_get_page_permalink( 'shop' )
			: home_url( '/shop/' );
	}

	$shop_glow_url   = ! empty( $atts['shop_glow_url'] ) ? $atts['shop_glow_url'] : $shop_url;
	$ajax            = admin_url( 'admin-ajax.php' );
	$uid             = 'glowfs_' . wp_rand( 1000, 9999 );
	$show_icon       = filter_var( $atts['show_icon'], FILTER_VALIDATE_BOOLEAN );
	$show_header     = ! empty( $atts['title'] ) && filter_var( $atts['show_header'], FILTER_VALIDATE_BOOLEAN );
	$show_glow       = filter_var( $atts['show_glow'], FILTER_VALIDATE_BOOLEAN );
	$show_gratitude  = filter_var( $atts['show_gratitude'], FILTER_VALIDATE_BOOLEAN );
	$is_minimal      = ( 'minimal' === $atts['layout'] ) || ( ! $show_icon && ! $show_glow && ! $show_gratitude );

	// Check for existing session or cookies
	$session_fid    = '';
	$session_seller = '';

	if ( function_exists( 'WC' ) && WC()->session ) {
		$session_fid    = WC()->session->get( 'fridID' );
		if ( empty( $session_fid ) ) {
			$session_fid = WC()->session->get( 'popup_fundraiser_id' );
		}
		$session_seller = WC()->session->get( 'seller_name' );
	}
	if ( empty( $session_fid ) && session_status() === PHP_SESSION_ACTIVE && isset( $_SESSION['fridID'] ) ) {
		$session_fid = $_SESSION['fridID'];
	}
	if ( empty( $session_seller ) && session_status() === PHP_SESSION_ACTIVE && isset( $_SESSION['seller_name'] ) ) {
		$session_seller = $_SESSION['seller_name'];
	}
	if ( empty( $session_fid ) && isset( $_COOKIE['fundraiserID'] ) ) {
		$session_fid = sanitize_text_field( $_COOKIE['fundraiserID'] );
	}
	if ( empty( $session_seller ) && isset( $_COOKIE['seller_name'] ) ) {
		$session_seller = sanitize_text_field( $_COOKIE['seller_name'] );
	}

	$has_preselected = ! empty( $session_fid );
	$org_name = '';
	if ( $has_preselected ) {
		$user = get_userdata( (int) $session_fid );
		if ( $user ) {
			$org_name = get_user_meta( $user->ID, 'user_registration_name_of_school_business', true );
			if ( empty( $org_name ) ) {
				$org_name = get_user_meta( $user->ID, 'user_registration_input_company', true );
			}
			if ( empty( $org_name ) ) {
				$org_name = trim( $user->first_name . ' ' . $user->last_name );
			}
			if ( empty( $org_name ) ) {
				$org_name = $user->display_name;
			}
		}
		if ( empty( $org_name ) ) {
			$posts = get_posts( array(
				'post_type'   => 'fundraiser',
				'meta_key'    => 'fundraiser_id',
				'meta_value'  => $session_fid,
				'numberposts' => 1,
			) );
			if ( ! empty( $posts ) ) {
				$org_name = function_exists( 'get_field' ) ? get_field( 'organizations_name', $posts[0]->ID ) : '';
				if ( empty( $org_name ) ) {
					$org_name = $posts[0]->post_title;
				}
			}
		}
	}

	ob_start();
	?>

	<div class="glow-fs-wrap <?php echo $is_minimal ? 'glow-fs-minimal' : 'glow-fs-card'; ?>" id="<?php echo esc_attr( $uid ); ?>">

		<?php if ( $show_icon ) : ?>
		<!-- Shopping-bag icon -->
		<div class="glow-fs-icon-ring" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" width="34" height="34"
				viewBox="0 0 24 24" fill="none"
				stroke="#ffffff" stroke-width="2"
				stroke-linecap="round" stroke-linejoin="round">
				<path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
				<line x1="3" y1="6" x2="21" y2="6"/>
				<path d="M16 10a4 4 0 01-8 0"/>
				<path d="M9 14s.5 1.5 3 1.5 3-1.5 3-1.5"/>
			</svg>
		</div>
		<?php endif; ?>

		<?php if ( $show_header ) : ?>
		<!-- Heading -->
		<h2 class="glow-fs-title"><?php echo esc_html( $atts['title'] ); ?></h2>
		<?php if ( ! empty( $atts['subtitle'] ) ) : ?>
		<p  class="glow-fs-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
		<?php endif; ?>
		<?php endif; ?>

		<?php if ( $has_preselected ) : ?>
		<!-- PRESELECTED SUMMARY: Shown automatically if fundraiser/seller exists in session -->
		<div class="glow-fs-preselected">
			<div class="glow-fs-id-chip">
				<div style="line-height:1.4;">
					<div class="glow-fs-id-chip-label">
						#<?php echo esc_html( $session_fid ); ?><?php echo ! empty( $org_name ) ? ' &middot; ' . esc_html( $org_name ) : ''; ?>
					</div>
					<?php if ( ! empty( $session_seller ) ) : ?>
					<div style="font-size:13px; color:#15803d; font-weight:600; margin-top:2px;">
						Seller: <?php echo esc_html( $session_seller ); ?>
					</div>
					<?php endif; ?>
				</div>
				<button type="button" class="glow-fs-change-preselected-btn glow-fs-change-btn" title="Change Fundraiser / Seller">&#9998; Change</button>
			</div>
		</div>
		<?php endif; ?>

		<!-- FIELDS ROW: Fundraiser ID + Seller Name side by side -->
		<div class="glow-fs-fields" <?php echo $has_preselected ? 'style="display:none;"' : ''; ?>>

		<!-- Fundraiser ID -->
		<div class="glow-fs-step glow-fs-s1">
			<div class="glow-fs-field-group">
				<label class="glow-fs-label" for="<?php echo esc_attr( $uid ); ?>_fid">
					FUNDRAISER ID
				</label>
				<div class="glow-fs-input-wrap">
					<input
						type="number"
						id="<?php echo esc_attr( $uid ); ?>_fid"
						inputmode="numeric"
						class="glow-fs-fid glow-fs-input"
						placeholder="FUNDRAISER ID"
						maxlength="6"
						autocomplete="off"
					/>
					<span class="glow-fs-spinner" aria-hidden="true" style="display:none;"></span>
				</div>
				<span class="glow-fs-org" style="display:none;"></span>
			</div>
		</div>

		<!-- Seller (visible together with Fundraiser ID) -->
		<div class="glow-fs-step glow-fs-s2">

			<!-- Locked fundraiser chip -->
			<div class="glow-fs-id-chip">
				<span class="glow-fs-id-chip-label"></span>
				<button type="button" class="glow-fs-change-btn" title="Change Fundraiser ID">&#9998; Change</button>
			</div>

			<!-- Seller field -->
			<div class="glow-fs-field-group">
				<label class="glow-fs-label">SELLER NAME</label>
				<select class="glow-fs-seller-select glow-fs-input" style="display:none;">
					<option value="">— Select Seller —</option>
				</select>
				<input
					type="text"
					class="glow-fs-custom-name glow-fs-input"
					placeholder="SELLER NAME"
					autocomplete="off"
				/>
			</div>
		</div>

		</div><!-- /.glow-fs-fields -->

		<!-- Inline message -->
		<p class="glow-fs-msg" role="alert" style="display:none;"></p>

		<!-- Primary CTA -->
		<button type="button" class="glow-fs-shop-btn" <?php echo ( $has_preselected && ! empty( $session_seller ) ) ? '' : 'disabled'; ?>>
			<?php echo esc_html( $atts['button'] ); ?> &rarr;
		</button>

		<?php if ( $show_gratitude ) : ?>
		<!-- Gratitude -->
		<p class="glow-fs-gratitude" aria-hidden="true">
			<span>&#10024;</span> We&#8217;re grateful for your support! <span>&#10024;</span>
		</p>
		<?php endif; ?>

		<?php if ( $show_glow ) : ?>
		<!-- Fallback: Shop without fundraiser -->
		<div class="glow-fs-nogift">
			<div class="glow-fs-or" aria-hidden="true"><span>OR</span></div>
			<div class="glow-fs-nogift-row">
				<span class="glow-fs-nogift-text">No fundraiser to support?</span>
				<a href="<?php echo esc_url( $shop_glow_url ); ?>" class="glow-fs-glow-btn">
					<?php echo esc_html( $atts['shop_glow_label'] ); ?> &rarr;
				</a>
			</div>
		</div>
		<?php endif; ?>

	</div>

	<style>
	/* ── Reset ──────────────────────────────────────────────── */
	.glow-fs-wrap *, .glow-fs-wrap *::before, .glow-fs-wrap *::after { box-sizing: border-box; }

	/* ── Wrap Layout ────────────────────────────────────────── */
	.glow-fs-wrap {
		position: relative;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
		color: #1a1a1a;
	}
	.glow-fs-wrap.glow-fs-card {
		max-width: 520px;
		margin: 52px auto 0;
		background: #fff;
		border-radius: 22px;
		padding: 64px 40px 36px;
		box-shadow: 0 12px 48px rgba(0,0,0,.13);
		text-align: center;
	}
	@media (max-width: 540px) {
		.glow-fs-wrap.glow-fs-card { padding: 56px 22px 28px; margin-top: 46px; border-radius: 16px; }
	}
	.glow-fs-wrap.glow-fs-minimal {
		max-width: 100%;
		margin: 0;
		padding: 0;
		background: transparent;
		box-shadow: none;
		border-radius: 0;
	}

	/* ── Icon ring ──────────────────────────────────────────── */
	.glow-fs-icon-ring {
		position: absolute;
		top: -38px; left: 50%;
		transform: translateX(-50%);
		width: 76px; height: 76px;
		background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
		border-radius: 50%;
		display: flex; align-items: center; justify-content: center;
		box-shadow: 0 6px 22px rgba(34,197,94,.45);
		border: 4px solid #fff;
	}

	/* ── Heading ────────────────────────────────────────────── */
	.glow-fs-title {
		font-size: 26px; font-weight: 800; line-height: 1.25;
		margin: 0 0 10px; color: #111;
		font-family: Georgia, 'Times New Roman', serif;
	}
	@media (max-width: 440px) { .glow-fs-title { font-size: 21px; } }

	.glow-fs-subtitle {
		font-size: 14px; color: #666; line-height: 1.55;
		margin: 0 auto 26px; max-width: 340px;
	}

	/* ── Steps ──────────────────────────────────────────────── */
	.glow-fs-step { margin-bottom: 4px; }

	/* ── Field groups ───────────────────────────────────────── */
	.glow-fs-field-group { position: relative; text-align: left; margin-bottom: 16px; }
	.glow-fs-input-wrap  { position: relative; }

	.glow-fs-label {
		display: block; font-size: 14px; font-weight: 700;
		letter-spacing: .02em; text-transform: uppercase;
		color: #0b3954; margin-bottom: 8px;
	}
	.glow-fs-input {
		width: 100%; padding: 14px 18px;
		border: 1.5px solid #ffb399; border-radius: 10px;
		font-size: 15px; color: #111; background: #fff;
		outline: none;
		transition: border-color .2s, box-shadow .2s;
		-moz-appearance: textfield;
	}
	.glow-fs-input::placeholder {
		color: #94a3b8;
		font-weight: 400;
		letter-spacing: .02em;
		opacity: 1;
	}
	.glow-fs-input::-webkit-inner-spin-button,
	.glow-fs-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
	.glow-fs-input:focus {
		border-color: #ff8a65;
		box-shadow: 0 0 0 3px rgba(255, 179, 153, .35);
		background: #fff;
	}
	.glow-fs-input:disabled { opacity: .55; cursor: not-allowed; }

	/* ── Spinner ────────────────────────────────────────────── */
	.glow-fs-spinner {
		position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
		width: 18px; height: 18px;
		border: 2.5px solid #e2e2e2; border-top-color: #22c55e;
		border-radius: 50%;
		animation: glowSpin .7s linear infinite;
	}
	@keyframes glowSpin { to { transform: translateY(-50%) rotate(360deg); } }

	/* ── ID chip ─────────────────────────────────────────────── */
	.glow-fs-id-chip {
		display: flex; align-items: center; justify-content: space-between;
		background: #f0fdf4; border: 1.5px solid #bbf7d0;
		border-radius: 10px; padding: 11px 16px; margin-bottom: 16px;
		text-align: left;
	}
	.glow-fs-id-chip-label { font-size: 14px; font-weight: 700; color: #166534; }
	.glow-fs-change-btn {
		background: none; border: none; font-size: 12px;
		color: #16a34a; cursor: pointer; padding: 0 4px;
		opacity: .8; transition: opacity .15s; font-weight: 600;
	}
	.glow-fs-change-btn:hover { opacity: 1; }

	/* ── Message ────────────────────────────────────────────── */
	.glow-fs-msg { font-size: 13px; margin: -6px 0 14px; text-align: left; color: #dc2626; }

	/* ── Primary button ─────────────────────────────────────── */
	.glow-fs-shop-btn {
		width: 100%; padding: 17px 20px; border: none; border-radius: 12px;
		background: linear-gradient(90deg, #ef4444 0%, #f97316 100%);
		color: #fff; font-size: 15px; font-weight: 800;
		letter-spacing: .07em; text-transform: uppercase;
		cursor: pointer; margin-bottom: 22px;
		transition: opacity .2s, transform .15s, box-shadow .2s;
	}
	.glow-fs-minimal .glow-fs-shop-btn {
		margin-bottom: 0;
	}
	.glow-fs-shop-btn:not(:disabled):hover {
		opacity: .91; transform: translateY(-2px);
		box-shadow: 0 8px 24px rgba(239,68,68,.35);
	}
	.glow-fs-shop-btn:disabled { opacity: .42; cursor: not-allowed; }

	/* ── Gratitude ──────────────────────────────────────────── */
	.glow-fs-gratitude { font-size: 14px; color: #555; margin: 0 0 20px; }

	/* ── OR divider ─────────────────────────────────────────── */
	.glow-fs-or {
		display: flex; align-items: center; gap: 12px;
		font-size: 11px; font-weight: 700; letter-spacing: .08em;
		color: #bbb; margin-bottom: 14px;
	}
	.glow-fs-or::before, .glow-fs-or::after { content:''; flex:1; height:1px; background:#ececec; }

	/* ── No-fundraiser row ──────────────────────────────────── */
	.glow-fs-nogift-row {
		display: flex; align-items: center; justify-content: space-between; gap: 14px;
		background: #fff5f5; border: 1.5px solid #fecaca;
		border-radius: 12px; padding: 13px 18px; flex-wrap: wrap;
	}
	.glow-fs-nogift-text { font-size: 14px; color: #555; flex: 1; text-align: left; min-width: 120px; }
	.glow-fs-glow-btn {
		display: inline-block; padding: 9px 20px;
		border: 2px solid #e91e8c; border-radius: 30px;
		color: #e91e8c; font-size: 13px; font-weight: 700;
		text-decoration: none; white-space: nowrap;
		transition: background .2s, color .2s;
	}
	.glow-fs-glow-btn:hover { background: #e91e8c; color: #fff; }

	/* ── Side-by-side fields ────────────────────────────────── */
	.glow-fs-fields {
		display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px;
		margin-bottom: 4px;
	}
	.glow-fs-fields .glow-fs-step { margin-bottom: 0; min-width: 0; }
	.glow-fs-s2 .glow-fs-id-chip { display: none; } /* ID stays visible, no chip needed */
	.glow-fs-org {
		display: block; margin-top: 6px;
		font-size: 12px; font-weight: 600; color: #16a34a;
	}
	@media (max-width: 540px) {
		.glow-fs-fields { grid-template-columns: 1fr; }
	}
	</style>

	<script>
	(function () {
		'use strict';

		var root = document.getElementById(<?php echo wp_json_encode( $uid ); ?>);
		if ( ! root ) { return; }

		var AJAX     = <?php echo wp_json_encode( $ajax ); ?>;
		var SHOP_URL = <?php echo wp_json_encode( $shop_url ); ?>;

		/* ── Element refs ───────────────────────────────────── */
		var s1            = root.querySelector('.glow-fs-s1');
		var s2            = root.querySelector('.glow-fs-s2');
		var preselectedEl = root.querySelector('.glow-fs-preselected');
		var changePreBtn  = root.querySelector('.glow-fs-change-preselected-btn');
		var fidInput      = root.querySelector('.glow-fs-fid');
		var spinner       = root.querySelector('.glow-fs-spinner');
		var chipLabel     = root.querySelector('.glow-fs-id-chip-label');
		var changeBtn     = root.querySelector('.glow-fs-change-btn');
		var sellerSel     = root.querySelector('.glow-fs-seller-select');
		var customName    = root.querySelector('.glow-fs-custom-name');
		var shopBtn       = root.querySelector('.glow-fs-shop-btn');
		var msgEl         = root.querySelector('.glow-fs-msg');
		var fieldsRow     = root.querySelector('.glow-fs-fields');
		var orgEl         = root.querySelector('.glow-fs-org');

		var currentFid        = <?php echo wp_json_encode( (string) $session_fid ); ?>;
		var preselectedSeller = <?php echo wp_json_encode( (string) $session_seller ); ?>;
		var busy              = false;
		var autoTimer         = null;

		/* ── Helpers ────────────────────────────────────────── */
		function post( data ) {
			return fetch( AJAX, {
				method      : 'POST',
				credentials : 'same-origin',
				headers     : { 'Content-Type': 'application/x-www-form-urlencoded' },
				body        : new URLSearchParams( data ).toString()
			});
		}
		function showMsg( t, isErr ) {
			msgEl.textContent   = t;
			msgEl.style.color   = ( isErr === false ) ? '#16a34a' : '#dc2626';
			msgEl.style.display = 'block';
		}
		function hideMsg()     { msgEl.style.display   = 'none'; }
		function showSpinner() { spinner.style.display = 'block'; }
		function hideSpinner() { spinner.style.display = 'none'; }

		function isOther( sel ) {
			if ( ! sel || sel.style.display === 'none' ) { return false; }
			var v = sel.value;
			var t = sel.options && sel.selectedIndex >= 0
				? ( sel.options[ sel.selectedIndex ].text || '' ).toLowerCase() : '';
			return v === 'custom' || v === 'add-seller' || v === 'other' ||
			       t.indexOf('add seller') !== -1 || t.indexOf('other') !== -1;
		}

		/* ── Update CTA state ───────────────────────────────── */
		function updateBtn() {
			if ( ! currentFid ) { shopBtn.disabled = true; return; }
			var selShown  = sellerSel.style.display  !== 'none';
			var custShown = customName.style.display !== 'none';
			if ( selShown && isOther( sellerSel ) ) {
				shopBtn.disabled = ! customName.value.trim();
			} else if ( selShown ) {
				shopBtn.disabled = ! sellerSel.value;
			} else if ( custShown ) {
				shopBtn.disabled = ! customName.value.trim();
			} else {
				shopBtn.disabled = true;
			}
		}

		/* ── Transition to Step 2 ───────────────────────────── */
		function showStep2( fid, orgName ) {
			currentFid = fid;
			chipLabel.textContent = '#' + fid + ( orgName ? '  ·  ' + orgName : '' );
			if ( orgEl ) {
				orgEl.textContent   = '\u2713 ' + ( orgName ? orgName : '#' + fid );
				orgEl.style.display = 'block';
			}
			hideSpinner();
			shopBtn.disabled = true;
		}

		/* ── Reset to Step 1 ────────────────────────────────── */
		function resetToStep1() {
			if ( preselectedEl ) { preselectedEl.style.display = 'none'; }
			if ( fieldsRow ) { fieldsRow.style.display = ''; }
			if ( orgEl ) { orgEl.style.display = 'none'; }
			sellerSel.innerHTML      = '<option value="">— Select Seller —</option>';
			sellerSel.style.display  = 'none';
			customName.style.display = 'block';
			customName.value         = '';
			fidInput.value           = '';
			fidInput.disabled        = false;
			shopBtn.disabled         = true;
			currentFid               = '';
			preselectedSeller        = '';
			busy                     = false;
			hideMsg(); hideSpinner();
			fidInput.focus();
		}

		/* ── Validate + load sellers ────────────────────────── */
		function doValidate( fid ) {
			if ( busy ) { return; }
			busy = true;
			fidInput.disabled = true;
			shopBtn.disabled  = true;
			hideMsg(); showSpinner();

			var orgName = '';

			post({ action: 'glow_validate_fundraiser_id', fund_id: fid })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if ( ! res || ! res.success ) {
						var errMsg = ( res && res.data && res.data.message )
							? res.data.message
							: 'Fundraiser ID not found. Please double-check and try again.';
						showMsg( errMsg );
						fidInput.disabled = false; fidInput.focus();
						hideSpinner(); busy = false;
						return Promise.reject('invalid');
					}

					orgName = ( res.data && res.data.org_name ) ? res.data.org_name : '';

					return post({ action: 'seller_drop_down_list', request_id: fid, glow_seller: '' })
						.then(function (r) { return r.text(); });
				})
				.then(function (opts) {
					opts = (opts || '').trim();
					busy = false; fidInput.disabled = false;

					showStep2( fid, orgName );

					// Parse HTML options cleanly to isolate real sellers
					var tempDiv = document.createElement('div');
					tempDiv.innerHTML = opts;
					var rawOptions = tempDiv.querySelectorAll('option');
					var sellers = [];

					for ( var i = 0; i < rawOptions.length; i++ ) {
						var opt = rawOptions[i];
						var val = ( opt.getAttribute('value') || opt.value || '' ).trim();
						var txt = ( opt.textContent || '' ).trim();

						// Skip empty or No-seller placeholder
						if ( ! val || val === 'No-seller' || ! txt ) { continue; }
						// We'll append custom / add-seller option at the bottom
						if ( val === 'custom' || opt.classList.contains('add-seller') ) { continue; }

						sellers.push({ val: val, txt: txt });
					}

					if ( sellers.length > 0 ) {
						var html = '<option value="">— Select Seller —</option>';
						for ( var s = 0; s < sellers.length; s++ ) {
							html += '<option value="' + sellers[s].val + '">' + sellers[s].txt + '</option>';
						}
						html += '<option value="custom">Other / Add Seller</option>';

						sellerSel.innerHTML      = html;
						sellerSel.style.display  = 'block';
						customName.style.display = 'none';

						// Customer typed a seller name before the ID → keep it.
						var typed = customName.value.trim();
						if ( typed ) {
							var hit = false;
							for ( var k = 0; k < sellerSel.options.length; k++ ) {
								if ( sellerSel.options[k].text.trim().toLowerCase() === typed.toLowerCase() ) {
									sellerSel.selectedIndex = k; hit = true; break;
								}
							}
							if ( ! hit ) {
								sellerSel.value = 'custom';
								customName.style.display = 'block';
							}
						}
					} else {
						// No registered sellers → direct text input
						sellerSel.style.display  = 'none';
						customName.style.display = 'block';
						customName.focus();
					}
					updateBtn();
				})
				.catch(function (err) {
					if ( err !== 'invalid' ) {
						showMsg('Something went wrong. Please try again.');
						fidInput.disabled = false;
						hideSpinner(); busy = false;
					}
				});
		}

		/* ── Events: Fundraiser ID input ────────────────────── */
		fidInput.addEventListener('input', function () {
			clearTimeout( autoTimer );
			hideMsg();
			var val = ( fidInput.value || '' ).replace(/\D/g, '');
			if ( currentFid && val !== String( currentFid ) ) {
				var keep = isOther( sellerSel ) || sellerSel.style.display === 'none'
					? customName.value
					: ( sellerSel.selectedIndex > 0 ? sellerSel.options[ sellerSel.selectedIndex ].text : '' );
				currentFid               = '';
				sellerSel.style.display  = 'none';
				customName.style.display = 'block';
				customName.value         = keep;
				if ( orgEl ) { orgEl.style.display = 'none'; }
				updateBtn();
			}
			if ( val.length === 6 ) {
				autoTimer = setTimeout(function () { doValidate( val ); }, 350);
			}
		});
		fidInput.addEventListener('keydown', function (e) {
			if ( e.key !== 'Enter' ) { return; }
			clearTimeout( autoTimer );
			var val = ( fidInput.value || '' ).replace(/\D/g, '');
			val.length === 6 ? doValidate( val ) : showMsg('Please enter a valid 6-digit Fundraiser ID.');
		});

		/* ── Events: Seller dropdown ────────────────────────── */
		sellerSel.addEventListener('change', function () {
			customName.style.display = isOther( sellerSel ) ? 'block' : 'none';
			if ( isOther( sellerSel ) ) { customName.value = ''; customName.focus(); }
			updateBtn();
		});
		customName.addEventListener('input', updateBtn);

		/* ── Events: Change ID ──────────────────────────────── */
		changeBtn.addEventListener('click', resetToStep1);
		if ( changePreBtn ) {
			changePreBtn.addEventListener('click', resetToStep1);
		}

		/* ── Events: Start Shopping ─────────────────────────── */
		shopBtn.addEventListener('click', function () {
			var custShown  = customName.style.display !== 'none';
			var sellerName = '';

			if ( preselectedEl && preselectedEl.style.display !== 'none' && preselectedSeller ) {
				sellerName = preselectedSeller;
			} else if ( custShown || isOther( sellerSel ) ) {
				sellerName = customName.value.trim();
				if ( ! sellerName ) { showMsg('Please enter the seller name.'); customName.focus(); return; }
			} else {
				var opt = sellerSel.options[ sellerSel.selectedIndex ];
				sellerName = opt ? opt.text : '';
			}
			if ( ! sellerName ) { return; }

			shopBtn.disabled    = true;
			shopBtn.textContent = 'Loading\u2026';
			hideMsg();

			post({
				action                : 'save_fundraiser_session',
				popup_fundraiser_id   : currentFid,
				popup_fundraiser_name : sellerName,
				profile_link          : SHOP_URL
			})
			.then(function (r) { return r.json(); })
			.then(function (res) {
				var url = ( res && res.data && res.data.profile_link ) ? res.data.profile_link : SHOP_URL;
				window.location.href = url;
			})
			.catch(function () { window.location.href = SHOP_URL; });
		});

	})();
	</script>

	<?php
	return ob_get_clean();
}
