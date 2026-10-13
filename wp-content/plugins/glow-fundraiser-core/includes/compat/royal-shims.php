<?php
/**
 * Compatibility shims for Royal-theme template helpers.
 *
 * The bundled legacy templates (glow-fundraiser-core/legacy/templates/*, and some
 * ported into the child theme) were written for the old Royal theme and call
 * et_page_config() to get their sidebar / content-layout classes. Royal is no
 * longer the active theme, so without this shim those templates fatal with
 * "Call to undefined function et_page_config()" immediately after get_header() —
 * leaving ONLY the site header/footer visible with no page content.
 *
 * This returns a sane full-width, no-sidebar layout config. Guarded with
 * function_exists so it never collides if Royal (or anything else) defines it.
 *
 * Note: et_page_heading is only ever used via do_action('et_page_heading'), which
 * is harmless with no callbacks attached, so no shim is needed for it.
 */
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'et_page_config' ) ) {
	/**
	 * @return array Layout config consumed as CSS-class suffixes by legacy templates.
	 */
	function et_page_config() {
		return array(
			'sidebar'             => 'no',
			'sidebar-mobile'      => 'no',
			'shop-sidebar-mobile' => 'no',
			'content-class'       => '',
		);
	}
}
