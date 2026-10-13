<?php
/**
 * Fundraiser & Seller Profile Routing & Template Loader
 *
 * Handles URL rewrites and template routing for:
 * - /profile/{id} -> Fundraiser profile page
 * - /seller/{id}  -> Seller profile page
 * - /seller/user/{login} -> Seller profile by user login
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Register Rewrite Rules
 */
add_action( 'init', 'glow_register_profile_rewrite_rules', 10 );
function glow_register_profile_rewrite_rules() {
	// Profile rewrite
	add_rewrite_rule(
		'^profile/([a-zA-Z0-9_-]+)/?$',
		'index.php?profile=$matches[1]',
		'top'
	);

	// Seller ID rewrite
	add_rewrite_rule(
		'^seller/([a-zA-Z0-9_-]+)/?$',
		'index.php?seller=$matches[1]',
		'top'
	);

	// Seller User login rewrite
	add_rewrite_rule(
		'^seller/user/([a-zA-Z0-9-_.]+)/?$',
		'index.php?user=$matches[1]',
		'top'
	);
}

/**
 * 2. Register Query Variables
 */
add_filter( 'query_vars', 'glow_register_profile_query_vars' );
function glow_register_profile_query_vars( $query_vars ) {
	$query_vars[] = 'profile';
	$query_vars[] = 'seller';
	$query_vars[] = 'user';
	return $query_vars;
}

/**
 * 3. Route Template Loader
 */
add_filter( 'template_include', 'glow_profile_template_include', 99 );
function glow_profile_template_include( $template ) {
	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';

	// Fundraiser Profile
	if ( get_query_var( 'profile' ) || preg_match( '#/profile/([a-zA-Z0-9_-]+)#i', $req_uri ) ) {
		// If query_var wasn't set by rewrite yet, populate it from URI
		if ( ! get_query_var( 'profile' ) && preg_match( '#/profile/([a-zA-Z0-9_-]+)#i', $req_uri, $matches ) ) {
			set_query_var( 'profile', $matches[1] );
		}

		// If /profile/{id} actually points at a SELLER, render the seller profile
		// instead. The fundraiser profile queries orders by fundraiser_field, so a
		// seller viewed here shows zero sales; the seller profile queries
		// wc_seller_field, which is where a seller's sales are recorded.
		$glow_pid   = get_query_var( 'profile' );
		$glow_puser = is_numeric( $glow_pid )
			? get_user_by( 'id', (int) $glow_pid )
			: ( get_user_by( 'login', $glow_pid ) ?: get_user_by( 'slug', $glow_pid ) );
		if ( $glow_puser && in_array( 'seller', (array) $glow_puser->roles, true ) ) {
			set_query_var( 'seller', $glow_puser->ID );
			foreach ( array(
				get_stylesheet_directory() . '/template-sellerprofile.php',
				get_template_directory() . '/template-sellerprofile.php',
				GLOW_FC_DIR . 'legacy/templates/template-sellerprofile.php',
			) as $glow_seller_tpl ) {
				if ( file_exists( $glow_seller_tpl ) ) {
					return $glow_seller_tpl;
				}
			}
		}

		// Look for template in theme or plugin legacy templates
		if ( file_exists( get_stylesheet_directory() . '/template-profile.php' ) ) {
			return get_stylesheet_directory() . '/template-profile.php';
		}
		if ( file_exists( get_template_directory() . '/template-profile.php' ) ) {
			return get_template_directory() . '/template-profile.php';
		}

		$plugin_tpl = GLOW_FC_DIR . 'legacy/templates/template-profile.php';
		if ( file_exists( $plugin_tpl ) ) {
			return $plugin_tpl;
		}
	}

	// Seller Profile
	if ( get_query_var( 'seller' ) || get_query_var( 'user' ) || preg_match( '#/seller/([a-zA-Z0-9_.-]+)#i', $req_uri ) ) {
		if ( ! get_query_var( 'seller' ) && ! get_query_var( 'user' ) && preg_match( '#/seller/([a-zA-Z0-9_.-]+)#i', $req_uri, $matches ) ) {
			set_query_var( 'seller', $matches[1] );
		}

		if ( file_exists( get_stylesheet_directory() . '/template-sellerprofile.php' ) ) {
			return get_stylesheet_directory() . '/template-sellerprofile.php';
		}
		if ( file_exists( get_template_directory() . '/template-sellerprofile.php' ) ) {
			return get_template_directory() . '/template-sellerprofile.php';
		}

		$plugin_tpl = GLOW_FC_DIR . 'legacy/templates/template-sellerprofile.php';
		if ( file_exists( $plugin_tpl ) ) {
			return $plugin_tpl;
		}
	}

	return $template;
}

/**
 * 4. Page Title Filters
 */
add_filter( 'wpseo_title', 'glow_profile_seo_title', 10000, 2 );
function glow_profile_seo_title( $title, $id = null ) {
	if ( get_query_var( 'profile' ) ) {
		$idg = get_query_var( 'profile' );
		$org = get_user_meta( $idg, 'user_registration_name_of_school_business', true );
		if ( ! empty( $org ) ) {
			return $org . ' | Glow Scented Candles';
		}
	} elseif ( get_query_var( 'seller' ) ) {
		$idg = get_query_var( 'seller' );
		$f   = get_user_meta( $idg, 'first_name', true );
		$l   = get_user_meta( $idg, 'last_name', true );
		return $f . ' ' . $l . ' | Support Now | Fund Raising';
	} elseif ( get_query_var( 'user' ) ) {
		$query = get_query_var( 'user' );
		$user  = get_user_by( 'login', $query );
		if ( $user ) {
			$idg = $user->ID;
			$f   = get_user_meta( $idg, 'first_name', true );
			$l   = get_user_meta( $idg, 'last_name', true );
			return $f . ' ' . $l;
		}
	}
	return $title;
}

/**
 * 5. Yoast Schema & Meta filters for custom endpoints
 */
add_filter( 'wpseo_json_ld_output', function( $data ) {
	if ( get_query_var( 'profile' ) || get_query_var( 'seller' ) || get_query_var( 'user' ) ) {
		return array();
	}
	return $data;
}, 10, 1 );

add_filter( 'wpseo_robots', function( $robots ) {
	if ( get_query_var( 'profile' ) || get_query_var( 'seller' ) || get_query_var( 'user' ) ) {
		return false;
	}
	return $robots;
} );
