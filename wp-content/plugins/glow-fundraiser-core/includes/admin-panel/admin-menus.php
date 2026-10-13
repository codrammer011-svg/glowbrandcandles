<?php
/**
 * Admin Menu Registration for Fundraiser Operations & Tools
 *
 * Registers top-level and submenu pages in WordPress admin dashboard.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'setup_theme_admin_menus' ) ) {
	function setup_theme_admin_menus() {
		add_menu_page( 'Email Settings', 'Email Settings', 'manage_options', 'edit_email', 'theme_edit_email_options' );
		add_menu_page( 'Fundraiser Operations', 'Fundraiser Operations', 'manage_options', 'fundraiser_operations', 'theme_fundraiser_operations_options' );
		add_menu_page( 'Fundraiser Outstanding', 'Fundraiser Outstanding', 'manage_options', 'fundraiser_outstanding', 'theme_fundraiser_outstanding_options' );
		add_menu_page( 'Send Check Orders', 'Send Check Orders', 'manage_options', 'send_check_orders', 'theme_fundraiser_send_check_orders' );
		add_menu_page( 'Email Tracking', 'Email Tracking', 'manage_options', 'email_tracking', 'theme_fundraiser_email_tracking' );
		add_menu_page( 'Booking List', 'Booking List', 'manage_options', 'booking_list', 'theme_fundraiser_booking_track' );
		add_menu_page( 'Representative', 'Representative', 'manage_options', 'representative', 'custom_representative_list_data', 'dashicons-admin-users' );
		add_submenu_page( 'representative', 'Add New Reps', 'Add New Reps', 'manage_options', 'new_user_custom-submenu-page', 'custom_add_new_reps_submenu_page_callback' );
	}
	add_action( 'admin_menu', 'setup_theme_admin_menus' );
}

if ( ! function_exists( 'theme_edit_email_options' ) ) {
	function theme_edit_email_options() {
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
		$seller_email     = get_option( 'name_option_you_want_seller', '<p>Mail format</p>' );
		$sales_email      = get_option( 'name_option_you_want_sales', '<p>Mail format</p>' );
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
