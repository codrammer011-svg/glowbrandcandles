<?php
/**
 * Theme Setup – enqueue styles
 * Auto-split from functions.php – edit this file, not the monolith.
 */

add_action('wp_enqueue_scripts', 'glow_scented_candles_enqueue_styles');
function glow_scented_candles_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
/*
 * Your code goes below
 */
/**
 * GLOW Scented Candles - Term Count for Elementor Loop Grid
 * Add this to functions.php or Code Snippets plugin
 */
