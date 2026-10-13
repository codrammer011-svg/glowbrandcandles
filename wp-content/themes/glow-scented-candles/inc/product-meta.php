<?php
/**
 * Product Meta Boxes & Query Filters
 * Auto-split from functions.php – edit this file, not the monolith.
 */

add_action( 'add_meta_boxes_product', function () {
    add_meta_box( 'glow_cat_order', 'Category Display Order', 'glow_cat_order_box', 'product', 'side', 'default' );
} );

if ( ! function_exists( 'glow_cat_order_box' ) ) {
    function glow_cat_order_box( $post ) {
        $terms = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'orderby'    => 'name',
        ) );
        $default_cat = (int) get_option( 'default_product_cat' );

        wp_nonce_field( 'glow_cat_order', 'glow_cat_order_nonce' );
        ?>
        <style>
            .glow-co-row { display: flex; align-items: center; gap: 8px; margin: 8px 0; }
            .glow-co-row[hidden] { display: none; }
            .glow-co-row label { flex: 1; font-weight: 600; }
            .glow-co-row input { width: 72px; }
        </style>
        <p class="description">1 = shows first in that category. Leave empty to show after the numbered products (best sellers first).</p>
        <p class="glow-co-empty"><em>Select a product category to set its order.</em></p>
        <?php
        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( (int) $term->term_id === $default_cat ) {
                    continue; /* skip "Uncategorized" */
                }
                $label = $term->name;
                if ( $term->parent ) {
                    $parent = get_term( $term->parent, 'product_cat' );
                    if ( $parent && ! is_wp_error( $parent ) ) {
                        $label = $parent->name . ' › ' . $term->name;
                    }
                }
                $value = get_post_meta( $post->ID, GLOW_CAT_ORDER_PREFIX . $term->term_id, true );
                printf(
                    '<p class="glow-co-row" data-term="%1$d" hidden><label for="glow_co_%1$d">%2$s</label><input type="number" min="0" step="1" id="glow_co_%1$d" name="glow_cat_order[%1$d]" value="%3$s" placeholder="Auto"></p>',
                    (int) $term->term_id,
                    esc_html( $label ),
                    esc_attr( $value )
                );
            }
        }
        ?>
        <p class="description"><em>New category added just now? Update the product once, then its field appears.</em></p>
        <script>
        (function () {
            /* Show a field only for the categories ticked in the "Product categories" box. */
            function sync() {
                var checked = {};
                document.querySelectorAll('#product_catchecklist input[type="checkbox"]').forEach(function (box) {
                    if (box.checked) { checked[box.value] = true; }
                });
                var any = false;
                document.querySelectorAll('.glow-co-row').forEach(function (row) {
                    var on = !!checked[row.getAttribute('data-term')];
                    row.hidden = !on;
                    if (on) { any = true; }
                });
                var empty = document.querySelector('.glow-co-empty');
                if (empty) { empty.hidden = any; }
            }
            document.addEventListener('change', function (e) {
                if (e.target.closest && e.target.closest('#product_catchecklist')) { sync(); }
            });
            var list = document.getElementById('product_catchecklist');
            if (list && window.MutationObserver) {
                new MutationObserver(sync).observe(list, { childList: true, subtree: true });
            }
            sync();
        })();
        </script>
        <?php
    }
}

/* ---------- Admin: save ---------- */
add_action( 'save_post_product', function ( $post_id ) {
    if ( ! isset( $_POST['glow_cat_order_nonce'] )
        || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['glow_cat_order_nonce'] ) ), 'glow_cat_order' ) ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $posted   = isset( $_POST['glow_cat_order'] ) && is_array( $_POST['glow_cat_order'] ) ? wp_unslash( $_POST['glow_cat_order'] ) : array();
    $assigned = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'ids' ) );
    $assigned = is_wp_error( $assigned ) ? array() : array_map( 'intval', $assigned );

    /* Remove numbers for categories the product left, or fields that were emptied. */
    foreach ( array_keys( (array) get_post_meta( $post_id ) ) as $key ) {
        if ( 0 !== strpos( $key, GLOW_CAT_ORDER_PREFIX ) ) {
            continue;
        }
        $term_id = (int) substr( $key, strlen( GLOW_CAT_ORDER_PREFIX ) );
        if ( ! in_array( $term_id, $assigned, true ) || ! isset( $posted[ $term_id ] ) || '' === trim( (string) $posted[ $term_id ] ) ) {
            delete_post_meta( $post_id, $key );
        }
    }

    foreach ( $assigned as $term_id ) {
        if ( isset( $posted[ $term_id ] ) && is_numeric( $posted[ $term_id ] ) ) {
            update_post_meta( $post_id, GLOW_CAT_ORDER_PREFIX . $term_id, max( 0, (int) $posted[ $term_id ] ) );
        }
    }
}, 20 );

/* ---------- Front end: which category is being viewed? ---------- */
if ( ! function_exists( 'glow_cat_order_term_id' ) ) {
    function glow_cat_order_term_id() {
        if ( function_exists( 'is_product_category' ) && is_product_category() ) {
            $term = get_queried_object();
            return ( $term instanceof WP_Term ) ? (int) $term->term_id : 0;
        }
        if ( function_exists( 'glow_shop_current_filters' ) ) {
            $filters = glow_shop_current_filters();
            if ( $filters['category'] && is_numeric( $filters['category'] ) ) {
                return (int) $filters['category'];
            }
            if ( $filters['scent'] ) {
                return (int) $filters['scent'];
            }
        }
        return 0;
    }
}

/* Mark the shop / category product queries that should use the custom order. */
add_action( 'pre_get_posts', function ( $query ) {
    if ( is_admin() || ! function_exists( 'glow_is_shop_context' ) || ! glow_is_shop_context() ) {
        return;
    }
    if ( ! function_exists( 'glow_is_product_query' ) || ! glow_is_product_query( $query ) ) {
        return;
    }
    if ( ! empty( $_GET['orderby'] ) ) {
        return; /* customer picked a sort (e.g. price) */
    }
    if ( ! $query->is_main_query() && defined( 'GLOW_SHOP_PER_PAGE' ) && (int) $query->get( 'posts_per_page' ) !== (int) GLOW_SHOP_PER_PAGE ) {
        return; /* sliders and other small loops keep their own order */
    }

    $term_id = glow_cat_order_term_id();
    if ( $term_id ) {
        $query->set( 'glow_cat_order_term', $term_id );
        $query->set( 'suppress_filters', false );
    }
}, 50 );

/* Numbered products first (1, 2, 3...), then the rest by best sellers. */
add_filter( 'posts_clauses', function ( $clauses, $query ) {
    $term_id = (int) $query->get( 'glow_cat_order_term' );
    if ( ! $term_id ) {
        return $clauses;
    }
    global $wpdb;
    $lookup = isset( $wpdb->wc_product_meta_lookup ) ? $wpdb->wc_product_meta_lookup : $wpdb->prefix . 'wc_product_meta_lookup';

    $clauses['join'] .= $wpdb->prepare(
        " LEFT JOIN {$wpdb->postmeta} AS glow_ord ON ( glow_ord.post_id = {$wpdb->posts}.ID AND glow_ord.meta_key = %s )",
        GLOW_CAT_ORDER_PREFIX . $term_id
    );
    $clauses['join'] .= " LEFT JOIN {$lookup} AS glow_ls ON ( glow_ls.product_id = {$wpdb->posts}.ID )";

    $clauses['orderby'] = "( glow_ord.meta_value IS NULL OR glow_ord.meta_value = '' ) ASC, "
        . "CAST( glow_ord.meta_value AS SIGNED ) ASC, "
        . "glow_ls.total_sales DESC, "
        . "{$wpdb->posts}.post_title ASC, {$wpdb->posts}.ID ASC";

    return $clauses;
}, 99, 2 );
