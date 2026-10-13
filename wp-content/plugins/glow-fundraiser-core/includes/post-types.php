<?php
/**
 * Custom post types: fundraiser, feedback, close_out, payment_history.
 * Moved out of the theme (functions.php + admin-panel/post-types.php).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Post type: Fundraiser (slug: fundraiser) */
function glow_register_fundraiser_cpt() {
	$labels = array(
		'name'               => _x( 'Fundraisers', 'Fundraisers', 'glow-fundraiser-core' ),
		'singular_name'      => _x( 'Fundraiser', 'Fundraiser', 'glow-fundraiser-core' ),
		'menu_name'          => __( 'fundraiser', 'glow-fundraiser-core' ),
		'all_items'          => __( 'All Fundraisers', 'glow-fundraiser-core' ),
		'view_item'          => __( 'View fundraiser', 'glow-fundraiser-core' ),
		'add_new_item'       => __( 'Add New Fundraisers', 'glow-fundraiser-core' ),
		'add_new'            => __( 'Add New', 'glow-fundraiser-core' ),
		'edit_item'          => __( 'Edit Fundraiser', 'glow-fundraiser-core' ),
		'update_item'        => __( 'Update Fundraiser', 'glow-fundraiser-core' ),
		'search_items'       => __( 'Search Fundraiser', 'glow-fundraiser-core' ),
		'not_found'          => __( 'Not Found', 'glow-fundraiser-core' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'glow-fundraiser-core' ),
	);
	$args = array(
		'label'               => __( 'fundraiser', 'glow-fundraiser-core' ),
		'description'         => __( 'fundraisers', 'glow-fundraiser-core' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
		'taxonomies'          => array( 'genres' ),
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
	register_post_type( 'fundraiser', $args );
}
add_action( 'init', 'glow_register_fundraiser_cpt', 0 );

/** Post type: Feedback (slug: feedback) */
function glow_register_feedback_cpt() {
	$labels = array(
		'name'               => _x( 'Feedback', 'feedback_name', 'glow-fundraiser-core' ),
		'singular_name'      => _x( 'Feedback', 'feedback_singular', 'glow-fundraiser-core' ),
		'add_new'            => _x( 'Add New', 'Feedback', 'glow-fundraiser-core' ),
		'add_new_item'       => __( 'Add New Feedback', 'glow-fundraiser-core' ),
		'edit_item'          => __( 'Edit Feedback', 'glow-fundraiser-core' ),
		'new_item'           => __( 'New Feedback', 'glow-fundraiser-core' ),
		'all_items'          => __( 'All Feedback', 'glow-fundraiser-core' ),
		'view_item'          => __( 'View Feedback', 'glow-fundraiser-core' ),
		'search_items'       => __( 'Search Feedback', 'glow-fundraiser-core' ),
		'not_found'          => __( 'No Feedback found', 'glow-fundraiser-core' ),
		'not_found_in_trash' => __( 'No Feedback found in the Trash', 'glow-fundraiser-core' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Feedback',
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Displays Feedback',
		'public'        => true,
		'menu_position' => 4,
		'supports'      => array( 'title', 'editor', 'thumbnail' ),
		'has_archive'   => true,
	);
	register_post_type( 'feedback', $args );
}
add_action( 'init', 'glow_register_feedback_cpt' );

/** Post type: Close Out Request (slug: close_out) */
function glow_register_close_out_cpt() {
	$labels = array(
		'name'               => 'Close Out Request',
		'singular_name'      => 'Close Out Request',
		'all_items'          => 'All Close Out Request',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Close Out Request',
		'edit_item'          => 'Edit Close Out Request',
		'new_item'           => 'New Close Out Request',
		'view_item'          => 'View Close Out Request',
		'search_items'       => 'Search Close Out Request',
		'not_found'          => 'No Close Out Request Found',
		'not_found_in_trash' => 'No Close Out Request in Trash',
		'parent_item_colon'  => '',
	);
	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'menu_position' => 5,
		'rewrite'       => array( 'slug' => 'close_out' ),
		'has_archive'   => true,
		'supports'      => array( 'title', 'custom-fields', 'editor', 'thumbnail' ),
	);
	register_post_type( 'close_out', $args );
}
add_action( 'init', 'glow_register_close_out_cpt' );

/** Post type: Payment History (slug: payment_history) */
function glow_register_payment_history_cpt() {
	$labels = array(
		'name'               => 'Payment History',
		'singular_name'      => 'payment_history',
		'all_items'          => 'All Payments',
		'add_new'            => 'Add New Record',
		'add_new_item'       => 'Add New Payment Record',
		'edit_item'          => 'Edit Payment Record',
		'new_item'           => 'New Payment Record',
		'view_item'          => 'View Payment Record',
		'search_items'       => 'Search Payment Record',
		'not_found'          => 'No Payment Record Found',
		'not_found_in_trash' => 'No Payment Record in Trash',
		'parent_item_colon'  => '',
	);
	$args = array(
		'labels'        => $labels,
		'public'        => true,
		'menu_position' => 7,
		'rewrite'       => array( 'slug' => 'payment_history' ),
		'has_archive'   => true,
		'supports'      => array( 'title', 'custom-fields', 'thumbnail' ),
	);
	register_post_type( 'payment_history', $args );
}
add_action( 'init', 'glow_register_payment_history_cpt' );
