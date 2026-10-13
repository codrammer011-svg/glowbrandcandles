<?php
/**
 * Shop Display – product display, account menu, filters
 * Auto-split from functions.php – edit this file, not the monolith.
 */

// ============================================================
// Add 'Profile' item below Dashboard in WooCommerce My Account
// ============================================================
add_filter('woocommerce_account_menu_items', function ($items) {
    $new_items = array();

    foreach ($items as $key => $title) {
        $new_items[$key] = $title;

        // Insert Profile immediately after dashboard
        if ($key === 'dashboard') {
            $new_items['profile'] = __('Profile', 'glow-scented-candles');
        }
    }

    return $new_items;
}, 20);

// Set URL for 'profile' endpoint to /profile/{user_id}
add_filter('woocommerce_get_endpoint_url', function ($url, $endpoint, $value, $permalink) {
    if ($endpoint === 'profile') {
        $current_user_id = get_current_user_id();
        return home_url('/profile/' . $current_user_id);
    }
    return $url;
}, 20, 4);

/**
 * Show WooCommerce short description in product archive cards
 */
add_action( 'woocommerce_shop_loop_item_title', 'custom_archive_product_short_description', 15 );

function custom_archive_product_short_description() {

    global $product;

    if ( ! $product ) {
        return;
    }

    $short_description = $product->get_short_description();

    if ( ! empty( $short_description ) ) {

        echo '<div class="custom-product-short-description">';
        echo wp_trim_words( wp_strip_all_tags( $short_description ), 20, '...' );
        echo '</div>';

    }
}

add_action( 'woocommerce_before_shop_loop_item_title', 'custom_woolentor_second_product_image', 11 );

function custom_woolentor_second_product_image() {

    global $product;

    if ( ! $product ) {
        return;
    }

    $gallery_ids = $product->get_gallery_image_ids();

    if ( empty( $gallery_ids ) ) {
        return;
    }

    $second_image_id = $gallery_ids[0];

    echo '<span class="custom-woolentor-secondary-image">';
    
    echo wp_get_attachment_image(
        $second_image_id,
        'woocommerce_thumbnail',
        false,
        array(
            'class' => 'custom-secondary-image',
            'alt'   => esc_attr( $product->get_name() ),
        )
    );

    echo '</span>';
}


/**
/**
 * GLOW SHOP - ALL SHOP PAGE CODE IN ONE PLACE
 * 1. Shop Filters (AJAX)
 * 2. Category Extra Image + Elementor dynamic tag
 * 3. Shop By Category / Shop By Scent section
 * 4. Pagination Fix v2
 * (The Cart Page code is separate: keep it where it is.)
 */
 
 
/* =====================================================================
 * 1. SHOP FILTERS
 * ===================================================================== */
 
/**
 * GLOW Shop Filters (AJAX) - ID based
 * Shortcode: [glow_shop_filters]
 * Optional:  [glow_shop_filters target=".my-products-wrapper"]
 * Paste into your child theme's functions.php or a Code Snippets PHP snippet.
 */
 
/* Categories dropdown: Label => product_cat term ID. Order here = order in dropdown.
   Sale! = products in the "sale-product" category. */
if ( ! function_exists( 'glow_shop_category_options' ) ) {
    function glow_shop_category_options() {
        $options = array(
            'Celebrate the Moments Candles' => 155,
            'Classic Candles'               => 152,
            'Signature 2 Wick Candles'      => 153,
            'Boxed Candles'                 => 151,
            'Wax Melts'                     => 154,
            'Essential Oils'                => 110,
            'Room Sprays'                   => 177,
            'Glow for Less'                 => 207,
        );
 
        $sale = get_term_by( 'slug', 'sale-product', 'product_cat' );
        if ( $sale && ! is_wp_error( $sale ) ) {
            $options['Sale!'] = (int) $sale->term_id;
        }
 
        return $options;
    }
}
 
/* Shop By Scent dropdown: Label => product_cat term ID. */
if ( ! function_exists( 'glow_shop_scent_options' ) ) {
    function glow_shop_scent_options() {
        return array(
            'Sweet & Spicy'   => 172,
            'Fruity & Citrus' => 173,
            'Fresh & Floral'  => 176,
            'Rich & Woodsy'   => 178,
        );
    }
}
 
/* Reads the URL and returns only valid selections. */
if ( ! function_exists( 'glow_shop_current_filters' ) ) {
    function glow_shop_current_filters() {
        $category = isset( $_GET['glow_category'] ) ? sanitize_key( wp_unslash( $_GET['glow_category'] ) ) : '';
        $scent    = isset( $_GET['glow_family'] ) && is_scalar( $_GET['glow_family'] ) ? absint( $_GET['glow_family'] ) : 0;
        $orderby  = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : '';
 
        if ( ! in_array( $category, array_map( 'strval', glow_shop_category_options() ), true ) ) {
            $category = '';
        }
        if ( ! in_array( $scent, glow_shop_scent_options(), true ) ) {
            $scent = 0;
        }
        if ( ! in_array( $orderby, array( 'price', 'price-desc' ), true ) ) {
            $orderby = '';
        }
 
        return array(
            'category' => $category,
            'scent'    => $scent,
            'orderby'  => $orderby,
        );
    }
}
 
/* Shortcode: [glow_shop_filters] */
add_shortcode( 'glow_shop_filters', function ( $atts ) {
    if ( ! function_exists( 'wc_get_page_id' ) ) {
        return '';
    }
 
    $shop_id = wc_get_page_id( 'shop' );
    if ( $shop_id <= 0 ) {
        return '';
    }
 
    $atts = shortcode_atts( array(
        'target' => '', // CSS selector of the products wrapper to refresh (optional)
    ), $atts, 'glow_shop_filters' );
 
    $current = glow_shop_current_filters();
 
    static $assets_printed = false;
 
    ob_start();
    ?>
    <form class="glow-shop-filters" method="get"
          action="<?php echo esc_url( get_permalink( $shop_id ) ); ?>"
          data-target="<?php echo esc_attr( $atts['target'] ); ?>">
 
        <div class="glow-filter-group">
            <strong>Filter By</strong>
            <div class="glow-filter-selects">
                <select name="glow_category" aria-label="Categories">
                    <option value="">Categories</option>
                    <?php foreach ( glow_shop_category_options() as $label => $value ) : ?>
                        <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current['category'], (string) $value ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
 
                <select name="glow_family" aria-label="Shop By Scent">
                    <option value="">Shop By Scent</option>
                    <?php foreach ( glow_shop_scent_options() as $label => $id ) : ?>
                        <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $current['scent'], $id ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
 
        <div class="glow-filter-group glow-sort-group">
            <strong>Sort</strong>
            <select name="orderby" aria-label="Product Price">
                <option value="" <?php selected( $current['orderby'], '' ); ?>>Product Price</option>
                <option value="price" <?php selected( $current['orderby'], 'price' ); ?>>Price: Low to High</option>
                <option value="price-desc" <?php selected( $current['orderby'], 'price-desc' ); ?>>Price: High to Low</option>
            </select>
        </div>
 
        <noscript><button type="submit">Apply</button></noscript>
        <span class="glow-filter-status" role="status" aria-live="polite"></span>
    </form>
 
    <?php if ( ! $assets_printed ) : $assets_printed = true; ?>
    <style>
        .glow-shop-filters {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            width: 100%;
            margin: 0 0 22px;
        }
        .glow-filter-group strong {
            display: block;
            margin-bottom: 18px;
            color: #252b34;
            font-size: 15px;
            font-weight: 700;
        }
        .glow-filter-selects {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .glow-shop-filters select {
            width: auto;
            min-width: 122px;
            height: 39px;
            padding: 7px 28px 7px 15px;
            border: 1px solid #ccc;
            border-radius: 0;
            background-color: #fff;
            color: #64676c;
            font-size: 14px;
            cursor: pointer;
        }
        .glow-sort-group select { min-width: 145px; }
        .glow-filter-status {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0 0 0 0);
        }
        .glow-loading {
            opacity: .45;
            pointer-events: none;
            transition: opacity .2s ease;
        }
        @media (max-width: 600px) {
            .glow-shop-filters { flex-direction: column; gap: 18px; }
            .glow-filter-group, .glow-sort-group { width: 100%; }
            .glow-filter-group strong { margin-bottom: 10px; }
        }
    </style>
 
    <script>
    (function () {
        if (window.glowFiltersInit) { return; }
        window.glowFiltersInit = true;
 
        /* Product cards used to locate the real product grid widget. */
        var CARD_SELECTORS = ['.ht-product', '.woolentor-product', 'li.product', '.product.type-product'];
 
        /* Pagination links (WooCommerce, Elementor, ShopLentor/WooLentor). */
        var PAGINATION_SELECTOR = '.glow-pagination a, .woocommerce-pagination a, .elementor-pagination a, .woolentor-pagination a, .wl-pagination a, .ht-pagination a, nav.pagination a, a.page-numbers';
 
        /* Generic wrappers, used only if no product card widget is found. */
        var TARGETS = [
            '.elementor-widget[class*="woolentor-product"]',
            '.elementor-widget[class*="wl-product"]',
            '.elementor-widget[class*="product-grid"]',
            '.elementor-widget-wc-archive-products',
            '.elementor-widget-woocommerce-products',
            '.woocommerce-products-wrapper',
            '#primary',
            '#main',
            '.site-main'
        ];
 
        var busy = false;
 
        function getForm() {
            return document.querySelector('.glow-shop-filters');
        }
 
        function setStatus(message) {
            var status = document.querySelector('.glow-filter-status');
            if (status) { status.textContent = message; }
        }
 
        function firstCard(root) {
            for (var i = 0; i < CARD_SELECTORS.length; i++) {
                var card = root.querySelector(CARD_SELECTORS[i]);
                if (card) { return card; }
            }
            return null;
        }
 
        /* The Elementor widget around an element, matched by its unique data-id. */
        function widgetTarget(el) {
            var widget = el && el.closest('.elementor-widget[data-id]');
            if (!widget) { return null; }
            return {
                selector: '.elementor-element[data-id="' + widget.getAttribute('data-id') + '"]',
                el: widget
            };
        }
 
        function trySelectors(doc, selectors) {
            for (var i = 0; i < selectors.length; i++) {
                var el = null;
                try { el = doc.querySelector(selectors[i]); } catch (error) { continue; }
                if (el) { return { selector: selectors[i], el: el }; }
            }
            return null;
        }
 
        /* hint = an element inside the grid (e.g. the clicked pagination link). */
        function findTarget(doc, hint) {
            var form = getForm();
            var custom = form ? form.getAttribute('data-target') : '';
            var found = trySelectors(doc, custom ? [custom, '.glow-products'] : ['.glow-products']);
            if (found) { return found; }
 
            if (hint) {
                found = widgetTarget(hint);
                if (found) { return found; }
            }
 
            found = widgetTarget(firstCard(doc));
            if (found) { return found; }
 
            return trySelectors(doc, TARGETS);
        }
 
        function buildUrl(form) {
            var params = new URLSearchParams();
            new FormData(form).forEach(function (value, key) {
                if (value !== '') { params.append(key, value); }
            });
            var query = params.toString();
            return form.action + (query ? (form.action.indexOf('?') > -1 ? '&' : '?') + query : '');
        }
 
        function syncSelects(url) {
            var params = new URL(url, window.location.href).searchParams;
            document.querySelectorAll('.glow-shop-filters select').forEach(function (select) {
                select.value = params.get(select.name) || '';
            });
        }
 
        function load(url, pushState, hint) {
            if (busy) { return; }
 
            var current = findTarget(document, hint);
            var finalUrl = url;
            if (!current) {
                /* Grid not found: fall back to a normal page load so filters still work. */
                window.location.href = url;
                return;
            }
 
            busy = true;
            current.el.classList.add('glow-loading');
            setStatus('Loading products…');
 
            fetch(url, {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (response) {
                if (!response.ok) { throw new Error('Request failed'); }
                if (response.url) { finalUrl = response.url; }
                return response.text();
            })
            .then(function (html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var fresh = doc.querySelector(current.selector);
                if (!fresh) { throw new Error('Product grid missing from response'); }
 
                var imported = document.importNode(fresh, true);
                current.el.replaceWith(imported);
 
                /* Keep [glow_pagination] in sync with the new results. */
                var newNavs = doc.querySelectorAll('.glow-pagination');
                document.querySelectorAll('.glow-pagination').forEach(function (nav, i) {
                    if (newNavs[i]) { nav.replaceWith(document.importNode(newNavs[i], true)); }
                });
 
                if (pushState) {
                    window.history.pushState({ glowFilters: true }, '', finalUrl);
                }
 
                syncSelects(finalUrl);
                setStatus('Products updated.');
 
                var top = imported.getBoundingClientRect().top;
                if (top < 0) {
                    window.scrollBy({ top: top - 100, behavior: 'smooth' });
                }
 
                if (window.jQuery) {
                    window.jQuery(document.body).trigger('glow_filters_loaded');
                }
            })
            .catch(function () {
                /* AJAX failed: load the page normally so the customer still sees correct results. */
                window.location.href = url;
            })
            .finally(function () {
                busy = false;
                var target = findTarget(document);
                if (target) { target.el.classList.remove('glow-loading'); }
            });
        }
 
        /* Filter and sort dropdowns */
        document.addEventListener('change', function (e) {
            var select = e.target.closest('.glow-shop-filters select');
            if (!select) { return; }
 
            /* Category and scent work one at a time: picking one clears the other. */
            if (select.value && select.name === 'glow_category') {
                var scent = select.form.querySelector('[name="glow_family"]');
                if (scent) { scent.value = ''; }
            } else if (select.value && select.name === 'glow_family') {
                var category = select.form.querySelector('[name="glow_category"]');
                if (category) { category.value = ''; }
            }
 
            load(buildUrl(select.form), true);
        });
 
        /* Enter key / submit */
        document.addEventListener('submit', function (e) {
            var form = e.target.closest('.glow-shop-filters');
            if (!form) { return; }
            e.preventDefault();
            load(buildUrl(form), true);
        });
 
        /* Pagination links: runs first (capture phase) so no other script
           can reload the grid back to page 1. */
        document.addEventListener('click', function (e) {
            var link = e.target.closest(PAGINATION_SELECTOR);
            if (!link || !getForm()) { return; }
 
            var href = link.getAttribute('href');
            if (!href || href === '#' || href.indexOf('javascript:') === 0) { return; }
            if (e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) { return; }
 
            /* Only our pagination or pagination that belongs to the product grid. */
            var ownNav = link.closest('.glow-pagination');
            var widget = link.closest('.elementor-widget');
            var inGrid = ownNav || link.closest('.woocommerce-pagination') || (widget && firstCard(widget));
            if (!inGrid) { return; }
 
            e.preventDefault();
            e.stopPropagation();
            /* Our pagination sits in its own widget: find the grid by its product cards. */
            load(link.href, true, ownNav ? null : link);
        }, true);
 
        /* Browser Back / Forward */
        window.addEventListener('popstate', function () {
            if (getForm()) { load(window.location.href, false); }
        });
    })();
    </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
} );
 
/* Applies the selected filter to a product query (WooCommerce loop or ShopLentor widget). */
if ( ! function_exists( 'glow_apply_shop_query_filters' ) ) {
    function glow_apply_shop_query_filters( $query ) {
        if ( $query->get( 'glow_filters_applied' ) ) {
            return;
        }
 
        $current = glow_shop_current_filters();
        if ( ! $current['category'] && ! $current['scent'] ) {
            return;
        }
        $query->set( 'glow_filters_applied', true );
 
        /* The dropdown choice replaces any category preset on the widget
           (NOT IN exclusions and non-category clauses are kept). */
        $tax_query = (array) $query->get( 'tax_query' );
        foreach ( $tax_query as $key => $clause ) {
            if ( is_array( $clause ) && isset( $clause['taxonomy'] ) && 'product_cat' === $clause['taxonomy']
                && ( ! isset( $clause['operator'] ) || 'NOT IN' !== strtoupper( $clause['operator'] ) ) ) {
                unset( $tax_query[ $key ] );
            }
        }
 
        if ( 'sale' === $current['category'] ) {
            /* Sale!: every product with an active sale price. */
            $sale_ids = function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : array();
            $query->set( 'post__in', $sale_ids ? array_map( 'intval', $sale_ids ) : array( 0 ) );
            $query->set( 'tax_query', $tax_query );
            return;
        }
 
        $filters = array();
        if ( $current['category'] ) {
            $filters[] = array(
                'taxonomy'         => 'product_cat',
                'field'            => 'term_id',
                'terms'            => array( (int) $current['category'] ),
                'include_children' => false,
            );
        }
        if ( $current['scent'] ) {
            $filters[] = array(
                'taxonomy'         => 'product_cat',
                'field'            => 'term_id',
                'terms'            => array( (int) $current['scent'] ),
                'include_children' => false,
            );
        }
 
        if ( isset( $tax_query['relation'] ) && 'OR' === strtoupper( $tax_query['relation'] ) ) {
            $tax_query = array( $tax_query );
        }
        $tax_query             = array_merge( $tax_query, $filters );
        $tax_query['relation'] = 'AND';
        $query->set( 'tax_query', $tax_query );
    }
}
 
/* Main WooCommerce shop loop */
add_action( 'woocommerce_product_query', 'glow_apply_shop_query_filters', 20 );
 
/* Other product loops on the Shop page (e.g. ShopLentor / Elementor widgets) */
add_action( 'pre_get_posts', function ( $query ) {
    if ( is_admin() || $query->is_main_query() || ! function_exists( 'wc_get_page_id' ) ) {
        return;
    }
 
    $shop_id = wc_get_page_id( 'shop' );
    if ( ! is_shop() && ( $shop_id <= 0 || ! is_page( $shop_id ) ) ) {
        return;
    }
    if ( ! in_array( 'product', (array) $query->get( 'post_type' ), true ) ) {
        return;
    }
 
    glow_apply_shop_query_filters( $query );
 
    $orderby = glow_shop_current_filters()['orderby'];
    if ( $orderby ) {
        $query->set( 'meta_key', '_price' );
        $query->set( 'orderby', 'meta_value_num' );
        $query->set( 'order', 'price' === $orderby ? 'ASC' : 'DESC' );
    }
}, 30 );
 
/* =====================================================================
 * 2. CATEGORY EXTRA IMAGE
 * ===================================================================== */
 