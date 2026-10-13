<?php
/**
 * Shop Pagination – per page, canonical, glow_pagination
 * Auto-split from functions.php – edit this file, not the monolith.
 */

add_filter( 'loop_shop_per_page', function () {
    return GLOW_SHOP_PER_PAGE;
}, 999 );
 
/* Is this a product query? On category pages the grid's query often has NO
   post_type set (only the product category), so check the taxonomy as well. */
if ( ! function_exists( 'glow_is_product_query' ) ) {
    function glow_is_product_query( $query ) {
        $post_types = array_filter( (array) $query->get( 'post_type' ) );
        if ( in_array( 'product', $post_types, true ) ) {
            return true;
        }
        if ( $post_types ) {
            return false; /* some other post type (posts, pages, menus...) */
        }
        if ( $query->get( 'product_cat' ) || $query->get( 'product_tag' ) ) {
            return true;
        }
        $product_taxes = array( 'product_cat', 'product_tag' );
        foreach ( (array) $query->get( 'tax_query' ) as $clause ) {
            if ( ! is_array( $clause ) ) {
                continue;
            }
            if ( isset( $clause['taxonomy'] ) && in_array( $clause['taxonomy'], $product_taxes, true ) ) {
                return true;
            }
            foreach ( $clause as $sub ) { /* nested groups */
                if ( is_array( $sub ) && isset( $sub['taxonomy'] ) && in_array( $sub['taxonomy'], $product_taxes, true ) ) {
                    return true;
                }
            }
        }
        return false;
    }
}
 
/* 2. The grid's own product query follows the page number in the URL
      (also for queries built with wc_get_products, and category pages). */
add_action( 'pre_get_posts', function ( $query ) {
    if ( is_admin() || $query->is_main_query() || ! glow_is_shop_context() ) {
        return;
    }
    if ( ! glow_is_product_query( $query ) ) {
        return;
    }
 
    if ( GLOW_PAGINATION_DEBUG ) {
        $GLOBALS['glow_debug_queries'][] = $query;
    }
 
    /* Only the grid (same per-page count); sliders and other loops are left alone. */
    if ( (int) $query->get( 'posts_per_page' ) !== (int) GLOW_SHOP_PER_PAGE || $query->get( 'offset' ) ) {
        return;
    }
 
    $page = glow_shop_requested_page();
    if ( $page > 1 && (int) $query->get( 'paged' ) < 2 ) {
        $query->set( 'paged', $page );
    }
}, 40 );
 
/* 3. Same for grids built on WooCommerce's products shortcode class
      (their results are cached, so the page must be set before the cache key is made). */
add_filter( 'woocommerce_shortcode_products_query', function ( $args ) {
    if ( ! glow_is_shop_context() ) {
        return $args;
    }
    if ( isset( $args['posts_per_page'] ) && (int) $args['posts_per_page'] === (int) GLOW_SHOP_PER_PAGE && empty( $args['offset'] ) ) {
        $page = glow_shop_requested_page();
        if ( $page > 1 && ( empty( $args['paged'] ) || (int) $args['paged'] < 2 ) ) {
            $args['paged'] = $page;
        }
    }
    return $args;
}, 99 );
 
/* 4. Never let WordPress redirect a shop /page/2/ URL back to page 1. */
add_filter( 'redirect_canonical', function ( $redirect_url ) {
    if ( glow_is_shop_context() && glow_shop_requested_page() > 1 ) {
        return false;
    }
    return $redirect_url;
}, 20 );
 
/* ---------------------------------------------------------------
 * DEBUG BOX (only when GLOW_PAGINATION_DEBUG is true, only admins)
 * ------------------------------------------------------------- */
add_action( 'wp_footer', function () {
    if ( ! GLOW_PAGINATION_DEBUG || ! current_user_can( 'manage_options' ) || ! glow_is_shop_context() ) {
        return;
    }
    global $wp_query;
 
    $rows = array(
        'URL'            => isset( $_SERVER['REQUEST_URI'] ) ? esc_html( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '',
        'Requested page' => glow_shop_requested_page(),
        'Main per page'  => (int) $wp_query->get( 'posts_per_page' ),
        'Main pages'     => (int) $wp_query->max_num_pages,
        'Main total'     => (int) $wp_query->found_posts,
    );
 
    $i = 1;
    foreach ( (array) ( isset( $GLOBALS['glow_debug_queries'] ) ? $GLOBALS['glow_debug_queries'] : array() ) as $q ) {
        $rows[ 'Product loop ' . $i++ ] = sprintf(
            'type=%s, paged=%d, per_page=%d, no_found_rows=%s, total=%d',
            esc_html( implode( ',', array_filter( (array) $q->get( 'post_type' ) ) ) ?: '(none)' ),
            (int) $q->get( 'paged' ),
            (int) $q->get( 'posts_per_page' ),
            $q->get( 'no_found_rows' ) ? 'yes' : 'no',
            (int) $q->found_posts
        );
    }
 
    echo '<div style="position:fixed;left:10px;bottom:10px;z-index:99999;max-width:440px;padding:10px 12px;background:#111;color:#fff;font:12px/1.5 monospace;border-radius:6px;opacity:.92">';
    echo '<strong>GLOW pagination debug</strong><br>';
    foreach ( $rows as $label => $value ) {
        echo esc_html( $label ) . ': ' . $value . '<br>'; // phpcs:ignore
    }
    echo '</div>';
}, 99 );
 
/* =====================================================================
 * 5. CATEGORY PAGES PAGINATION (?product-page=2 -> /page/2/)
 * ===================================================================== */
 
/* Pagination links on shop + category pages use /page/2/ (which the product grid
   reads) instead of ?product-page=2 (which it ignores). Filters in the URL are kept. */
add_filter( 'woocommerce_pagination_args', function ( $args ) {
    if ( ! glow_is_shop_context() ) {
        return $args;
    }
    $args['base']   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( array( 'add-to-cart', 'product-page' ), get_pagenum_link( 999999999, false ) ) ) );
    $args['format'] = '';
    return $args;
}, 99 );
 
/* Any old ?product-page=N link (bookmarks, other widgets) is sent to /page/N/. */
add_action( 'template_redirect', function () {
    if ( ! glow_is_shop_context() || empty( $_GET['product-page'] ) || ! is_scalar( $_GET['product-page'] ) ) {
        return;
    }
    $page = max( 1, absint( $_GET['product-page'] ) );
    wp_safe_redirect( remove_query_arg( 'product-page', get_pagenum_link( $page, false ) ), 302 );
    exit;
} );
 
/* =====================================================================
 * 6. GLOW PAGINATION SHORTCODE: [glow_pagination]
 *    Works on the Shop page and on category pages (same template).
 *    Turn OFF the pagination inside the ShopLentor product widget and
 *    put this shortcode below the product grid instead.
 * ===================================================================== */
add_shortcode( 'glow_pagination', function () {
    global $wp_query;
 
    if ( ! function_exists( 'glow_is_shop_context' ) || ! glow_is_shop_context() ) {
        return '';
    }
 
    $total   = (int) $wp_query->max_num_pages;
    $current = max( 1, (int) get_query_var( 'paged' ) );
 
    static $css_printed = false;
    ob_start();
 
    if ( ! $css_printed ) {
        $css_printed = true;
        ?>
        <style>
            .glow-pagination {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                gap: 10px;
                margin: 26px 0 10px;
            }
            .glow-pagination:empty { display: none; }
            .glow-pagination .page-numbers {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 42px;
                height: 42px;
                padding: 0 8px;
                background: #fff;
                border: 1.5px solid #e6197a;
                border-radius: 999px;
                color: #252b34;
                font-size: 15px;
                line-height: 1;
                text-decoration: none;
                transition: background .2s ease, color .2s ease, transform .2s ease;
            }
            .glow-pagination a.page-numbers:hover {
                background: #e6197a;
                color: #fff;
                transform: translateY(-1px);
            }
            .glow-pagination .page-numbers.current {
                background: #fbd3e1;
                color: #e6197a;
                font-weight: 600;
            }
            .glow-pagination .page-numbers.dots {
                min-width: auto;
                padding: 0 2px;
                background: transparent;
                border: 0;
            }
            .glow-pagination .page-numbers.prev,
            .glow-pagination .page-numbers.next { min-width: 48px; font-size: 18px; }
            @media (max-width: 480px) {
                .glow-pagination { gap: 6px; }
                .glow-pagination .page-numbers { min-width: 36px; height: 36px; font-size: 14px; }
            }
        </style>
        <?php
    }
 
    /* The nav is always printed (even with 1 page) so AJAX filters can update it. */
    echo '<nav class="glow-pagination" aria-label="Products pagination">';
    if ( $total > 1 ) {
        $base  = str_replace( 999999999, '%#%', remove_query_arg( array( 'add-to-cart', 'product-page' ), get_pagenum_link( 999999999, false ) ) );
        $links = paginate_links( array(
            'base'      => esc_url_raw( $base ),
            'format'    => '',
            'current'   => min( $current, $total ),
            'total'     => $total,
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
            'type'      => 'array',
            'end_size'  => 1,
            'mid_size'  => 2,
        ) );
        if ( $links ) {
            echo implode( '', $links ); // phpcs:ignore
        }
    }
    echo '</nav>';
 
    return ob_get_clean();
} );
 

