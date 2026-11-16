<?php
/*
	Template Name: Private Label (standalone)
*/

/**
 * GLOW Private Label landing page, built from the designer's package in /private-label/.
 * The page is shown exactly as designed, with its own header and footer, so the theme
 * and Elementor styles stay out of it. Images live in uploads/glow-private-label/.
 * Hidden from search engines unless the page has the custom field glow_pl_indexable = yes.
 */
defined( 'ABSPATH' ) || exit;

$glow_pl_dir    = get_stylesheet_directory() . '/private-label/';
$glow_pl_uri    = get_stylesheet_directory_uri() . '/private-label/';
$glow_pl_assets = trailingslashit( wp_upload_dir()['baseurl'] ) . 'glow-private-label/';

// The designer's markup: everything inside <body>, with image paths pointed at uploads.
$glow_pl_html = (string) file_get_contents( $glow_pl_dir . 'index.html' );
if ( preg_match( '#<body[^>]*>(.*)</body>#s', $glow_pl_html, $glow_pl_m ) ) {
	$glow_pl_html = $glow_pl_m[1];
}
$glow_pl_html = str_replace( array( 'src="assets/', 'data-image="assets/' ), array( 'src="' . esc_url( $glow_pl_assets ), 'data-image="' . esc_url( $glow_pl_assets ) ), $glow_pl_html );

$glow_pl_index = 'yes' === get_post_meta( get_queried_object_id(), 'glow_pl_indexable', true );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>GLOW Private Label | Glow Scented Candles</title>
	<meta name="description" content="Create your signature candle line with GLOW Private Label. Choose a luxury vessel, explore fragrances, and finish with your own branding and packaging.">
	<?php if ( ! $glow_pl_index ) : ?><meta name="robots" content="noindex, nofollow"><?php endif; ?>
	<link rel="canonical" href="<?php echo esc_url( get_permalink() ); ?>">
	<?php wp_site_icon(); ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo esc_url( $glow_pl_uri . 'style.css?ver=' . filemtime( $glow_pl_dir . 'style.css' ) ); ?>">
	<script defer src="<?php echo esc_url( $glow_pl_uri . 'script.js?ver=' . filemtime( $glow_pl_dir . 'script.js' ) ); ?>"></script>
</head>
<body>
<?php
echo $glow_pl_html; // phpcs:ignore WordPress.Security.EscapeOutput -- fixed markup shipped with the theme.
?>
</body>
</html>
