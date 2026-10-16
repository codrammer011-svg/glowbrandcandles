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


add_shortcode('hover_product_image', function ($atts) {
    $atts = shortcode_atts([
        'size'   => 'large',
        'link'   => 'yes',
        'effect' => 'soft', // fade | slide | zoom | up | soft
    ], $atts);

    $allowed = ['fade', 'slide', 'zoom', 'up', 'soft'];
    $effect  = in_array($atts['effect'], $allowed, true) ? $atts['effect'] : 'soft';

    $product = wc_get_product(get_the_ID());
    if (!$product) return '';

    $main_id  = $product->get_image_id();
    $gallery  = $product->get_gallery_image_ids();
    $hover_id = !empty($gallery) ? $gallery[0] : 0;

    $main  = $main_id
        ? wp_get_attachment_image($main_id, $atts['size'], false, ['class' => 'hpi-main'])
        : wc_placeholder_img($atts['size'], ['class' => 'hpi-main']);
    $hover = $hover_id
        ? wp_get_attachment_image($hover_id, $atts['size'], false, ['class' => 'hpi-hover', 'loading' => 'lazy'])
        : '';

    static $css_done = false;
    $css = '';
    if (!$css_done) {
        $css_done = true;
        $css = '<style>
        /* Base */
        .hpi-wrap{position:relative;display:block;overflow:hidden;width:100%;line-height:0;}
        .hpi-wrap img{display:block;width:100%;height:auto;margin:0;}
        .hpi-wrap .hpi-hover{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;}

        /* Gallery image na ho to halka zoom */
        .hpi-wrap:not(.has-hover) .hpi-main{transition:transform .6s ease;}
        .hpi-wrap:not(.has-hover):hover .hpi-main{transform:scale(1.05);}

        /* 1. Fade */
        .hpi-fade .hpi-main,.hpi-fade .hpi-hover{transition:opacity .4s ease;}
        .hpi-fade .hpi-hover{opacity:0;}
        .hpi-fade.has-hover:hover .hpi-main{opacity:0;}
        .hpi-fade.has-hover:hover .hpi-hover{opacity:1;}

        /* 2. Slide (side se) */
        .hpi-slide .hpi-main,.hpi-slide .hpi-hover{transition:transform .5s ease;}
        .hpi-slide .hpi-hover{transform:translateX(100%);}
        .hpi-slide.has-hover:hover .hpi-main{transform:translateX(-100%);}
        .hpi-slide.has-hover:hover .hpi-hover{transform:translateX(0);}

        /* 3. Zoom fade */
        .hpi-zoom .hpi-hover{opacity:0;transform:scale(1.15);transition:opacity .5s ease,transform .7s ease;}
        .hpi-zoom.has-hover:hover .hpi-hover{opacity:1;transform:scale(1);}

        /* 4. Slide up (neeche se) */
        .hpi-up .hpi-main,.hpi-up .hpi-hover{transition:transform .5s cubic-bezier(.65,0,.35,1);}
        .hpi-up .hpi-hover{transform:translateY(100%);}
        .hpi-up.has-hover:hover .hpi-main{transform:translateY(-30%);}
        .hpi-up.has-hover:hover .hpi-hover{transform:translateY(0);}

        /* 5. Soft luxury fade */
        .hpi-soft .hpi-main,.hpi-soft .hpi-hover{transition:opacity 1s ease,transform 1.5s ease;}
        .hpi-soft .hpi-hover{opacity:0;}
        .hpi-soft.has-hover:hover .hpi-main{opacity:0;transform:scale(1.03);}
        .hpi-soft.has-hover:hover .hpi-hover{opacity:1;transform:scale(1.06);}
        </style>';
    }

    $class = 'hpi-wrap hpi-' . $effect . ($hover ? ' has-hover' : '');
    $inner = $main . $hover;

    if ($atts['link'] === 'yes') {
        $html = '<a class="' . esc_attr($class) . '" href="' . esc_url(get_permalink($product->get_id())) . '">' . $inner . '</a>';
    } else {
        $html = '<div class="' . esc_attr($class) . '">' . $inner . '</div>';
    }

    return $css . $html;
});