<?php
/**
 * Elementor Loop & term_count shortcode
 * Auto-split from functions.php – edit this file, not the monolith.
 */

// ============================================================
// METHOD 1 - Capture term during Elementor loop item render
// ============================================================
add_action('elementor/theme/before_do_single', function () {
    try {
        if (!class_exists('\Elementor\Plugin'))
            return;

        $document = \Elementor\Plugin::$instance->documents->get_current();
        if (!$document)
            return;

        $post_id = $document->get_main_id();
        if (!$post_id)
            return;

        // Try getting term directly by post ID
        $term = get_term($post_id);
        if ($term && !is_wp_error($term)) {
            $GLOBALS['glow_current_loop_term'] = $term;
            return;
        }

        // Try WooCommerce product categories
        $term = get_term($post_id, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $GLOBALS['glow_current_loop_term'] = $term;
            return;
        }

        // Try product tags
        $term = get_term($post_id, 'product_tag');
        if ($term && !is_wp_error($term)) {
            $GLOBALS['glow_current_loop_term'] = $term;
            return;
        }

    } catch (Exception $e) {
        // Fail silently
    }
});

// ============================================================
// METHOD 2 - Hook into Elementor Pro loop builder
// ============================================================
add_action('elementor/loop_builder/before_render_item', function ($term_id) {
    if (!$term_id)
        return;

    // Try all possible taxonomies
    $taxonomies = array('product_cat', 'product_tag', 'category', 'post_tag');

    foreach ($taxonomies as $taxonomy) {
        $term = get_term($term_id, $taxonomy);
        if ($term && !is_wp_error($term)) {
            $GLOBALS['glow_current_loop_term'] = $term;
            return;
        }
    }

    // Try without taxonomy if above fails
    $term = get_term($term_id);
    if ($term && !is_wp_error($term)) {
        $GLOBALS['glow_current_loop_term'] = $term;
    }
}, 10, 1);

// ============================================================
// METHOD 3 - Catch term from global WP_Query during loop
// ============================================================
add_action('the_post', function ($post) {
    try {
        global $wp_query;

        if (
            isset($wp_query->queried_object)
            && $wp_query->queried_object instanceof WP_Term
        ) {
            $GLOBALS['glow_current_loop_term'] = $wp_query->queried_object;
        }
    } catch (Exception $e) {
        // Fail silently
    }
});

// ============================================================
// SHORTCODE - Outputs the product count
// [term_count]           outputs: 12 Products
// [term_count label="0"] outputs: 12 (no label)
// [term_count label="Items"] outputs: 12 Items
// ============================================================
add_shortcode('term_count', function ($atts) {
    $atts = shortcode_atts(array(
        'label' => 'Products',
    ), $atts);

    $count = null;
    $label = sanitize_text_field($atts['label']);

    // --- Try Method 1 & 2 global first ---
    if (!empty($GLOBALS['glow_current_loop_term'])) {
        $term = $GLOBALS['glow_current_loop_term'];
        if (isset($term->count)) {
            $count = $term->count;
        }
    }

    // --- Try queried object (works on single archive pages) ---
    if ($count === null) {
        $term = get_queried_object();
        if ($term instanceof WP_Term && isset($term->count)) {
            $count = $term->count;
        }
    }

    // --- Try global wp_query directly ---
    if ($count === null) {
        global $wp_query;
        if (
            isset($wp_query->queried_object)
            && $wp_query->queried_object instanceof WP_Term
        ) {
            $count = $wp_query->queried_object->count;
        }
    }

    // --- Nothing worked ---
    if ($count === null) {
        return '';
    }

    // Format output
    if ($label === '0' || $label === '') {
        return intval($count);
    }

    return intval($count) . ' ' . $label;
});

// ============================================================
// OPTIONAL - Reset global after each loop item renders
// ============================================================
add_action('elementor/theme/after_do_single', function () {
    unset($GLOBALS['glow_current_loop_term']);
});
