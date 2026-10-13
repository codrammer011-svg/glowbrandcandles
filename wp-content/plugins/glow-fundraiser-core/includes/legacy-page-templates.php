<?php
/**
 * Register the bundled legacy page templates (glow-fundraiser-core/legacy/templates/*.php)
 * with WordPress so they appear in the page "Template" dropdown and render when selected.
 *
 * WordPress only scans the ACTIVE theme for "Template Name:" headers, so after the
 * switch from the Royal theme to Hello Elementor these templates disappeared from the
 * dropdown even though the files were preserved in this plugin. This bridges them back.
 *
 * Behaviour:
 *  - A legacy template is offered ONLY if the active theme does not already provide a
 *    file of the same basename (so ported theme templates win and nothing is shadowed).
 *  - Templates are keyed by their ORIGINAL filename, so pages migrated from the old site
 *    (whose _wp_page_template meta stores the bare filename) resolve without re-saving.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'GLOW_FC_DIR' ) ) {
	return;
}

/**
 * Scan legacy/templates/ once and cache the [ filename => Template Name ] map.
 *
 * @return array
 */
function glow_fc_legacy_templates_map() {
	static $map = null;
	if ( null !== $map ) {
		return $map;
	}
	$map = array();
	$dir = GLOW_FC_DIR . 'legacy/templates/';
	foreach ( (array) glob( $dir . '*.php' ) as $file ) {
		$data = get_file_data( $file, array( 'name' => 'Template Name' ) );
		$name = isset( $data['name'] ) ? trim( $data['name'] ) : '';
		if ( '' === $name ) {
			continue;
		}
		// Clean malformed headers that leaked trailing comment/close markers.
		foreach ( array( '*/', '?' . '>' ) as $marker ) {
			$pos = strpos( $name, $marker );
			if ( false !== $pos ) {
				$name = substr( $name, 0, $pos );
			}
		}
		$name = trim( $name );
		if ( '' === $name ) {
			continue;
		}
		$map[ basename( $file ) ] = $name;
	}
	return $map;
}

/**
 * Add legacy templates to the page-template dropdown (unless the theme provides one).
 */
add_filter( 'theme_page_templates', 'glow_fc_register_legacy_page_templates', 20, 1 );
function glow_fc_register_legacy_page_templates( $templates ) {
	foreach ( glow_fc_legacy_templates_map() as $file => $name ) {
		if ( isset( $templates[ $file ] ) ) {
			continue; // already offered by the theme
		}
		if ( locate_template( $file ) ) {
			continue; // theme provides its own copy — let it win
		}
		$templates[ $file ] = $name;
	}
	return $templates;
}

/**
 * Load the actual legacy template file when a singular object has one assigned and
 * the active theme cannot resolve it.
 */
add_filter( 'template_include', 'glow_fc_include_legacy_page_template', 95 );
function glow_fc_include_legacy_page_template( $template ) {
	if ( ! is_singular() ) {
		return $template;
	}
	$slug = get_page_template_slug( get_queried_object_id() );
	if ( ! $slug ) {
		return $template;
	}
	// If the theme can resolve the assigned template, defer to it.
	if ( locate_template( basename( $slug ) ) ) {
		return $template;
	}
	$file = GLOW_FC_DIR . 'legacy/templates/' . basename( $slug );
	if ( is_file( $file ) ) {
		return $file;
	}
	return $template;
}
