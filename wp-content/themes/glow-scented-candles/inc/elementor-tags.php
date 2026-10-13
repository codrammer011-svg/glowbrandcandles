<?php
/**
 * Elementor Dynamic Tags & glow_shop_categories
 * Auto-split from functions.php – edit this file, not the monolith.
 */

add_action( 'elementor/dynamic_tags/register', function ( $dynamic_tags_manager ) {
 
    $dynamic_tags_manager->register_group( 'glow', array( 'title' => 'GLOW' ) );
 
    if ( ! class_exists( 'Glow_Category_Extra_Image_Tag' ) ) {
 
        class Glow_Category_Extra_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
 
            public function get_name() {
                return 'glow-category-extra-image';
            }
 
            public function get_title() {
                return 'Category Extra Image';
            }
 
            public function get_group() {
                return 'glow';
            }
 
            public function get_categories() {
                return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
            }
 
            protected function register_controls() {
                $options = array( '' => 'Current Category (archive page)' );
                $terms   = get_terms( array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                ) );
                if ( ! is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $options[ $term->term_id ] = $term->name;
                    }
                }
 
                $this->add_control( 'glow_term', array(
                    'label'   => 'Category',
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'options' => $options,
                    'default' => '',
                ) );
 
                $this->add_control( 'fallback', array(
                    'label' => 'Fallback Image',
                    'type'  => \Elementor\Controls_Manager::MEDIA,
                ) );
            }
 
            public function get_value( array $options = array() ) {
                $term_id = (int) $this->get_settings( 'glow_term' );
 
                if ( ! $term_id ) {
                    $current = get_queried_object();
                    if ( $current instanceof WP_Term && 'product_cat' === $current->taxonomy ) {
                        $term_id = $current->term_id;
                    }
                }
 
                $image_id = $term_id ? (int) get_term_meta( $term_id, GLOW_CAT_IMAGE_META, true ) : 0;
 
                if ( $image_id && wp_attachment_is_image( $image_id ) ) {
                    return array(
                        'id'  => $image_id,
                        'url' => wp_get_attachment_image_url( $image_id, 'full' ),
                    );
                }
 
                $fallback = $this->get_settings( 'fallback' );
                if ( ! empty( $fallback['url'] ) ) {
                    return $fallback;
                }
 
                return array( 'id' => '', 'url' => '' );
            }
        }
    }
 
    $dynamic_tags_manager->register( new Glow_Category_Extra_Image_Tag() );
} );
 
/* =====================================================================
 * 3. SHOP BY CATEGORY / SCENT SECTION
 * ===================================================================== */
 
/**
 * GLOW Shop By Category / Shop By Scent section (AJAX)
 * Shortcode: [glow_shop_categories]
 *
 * Needs the other two GLOW snippets on the site:
 *  - GLOW Shop Filters (dropdowns + AJAX)      -> categories, scents, AJAX loading
 *  - GLOW Category Extra Image                 -> the image shown in each circle
 *
 * Clicking an icon sets the matching filter dropdown and loads products via AJAX
 * (no page reload). Clicking the active icon again clears it. "View All" clears both.
 * Paste into your child theme's functions.php or a Code Snippets PHP snippet.
 */
 
/* Circle image: Extra Image first, then WooCommerce thumbnail, then placeholder. */
if ( ! function_exists( 'glow_cat_nav_image' ) ) {
    function glow_cat_nav_image( $term_id ) {
        $meta_key = defined( 'GLOW_CAT_IMAGE_META' ) ? GLOW_CAT_IMAGE_META : 'glow_extra_image';
        $image_id = (int) get_term_meta( $term_id, $meta_key, true );
        if ( ! $image_id ) {
            $image_id = (int) get_term_meta( $term_id, 'thumbnail_id', true );
        }
        if ( $image_id && wp_attachment_is_image( $image_id ) ) {
            return wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'alt' => '', 'loading' => 'lazy' ) );
        }
        return '<img src="' . esc_url( wc_placeholder_img_src( 'thumbnail' ) ) . '" alt="" loading="lazy">';
    }
}
 
if ( ! function_exists( 'glow_cat_nav_item' ) ) {
    function glow_cat_nav_item( $param, $value, $label, $is_active, $shop_url ) {
        $url      = add_query_arg( $param, $value, $shop_url );
        $meta_key = defined( 'GLOW_CAT_IMAGE_META' ) ? GLOW_CAT_IMAGE_META : 'glow_extra_image';
        $is_sale  = ( 'Sale!' === $label || 'sale' === (string) $value );
        $has_img  = is_numeric( $value ) && get_term_meta( (int) $value, $meta_key, true );
 
        if ( $is_sale && ! $has_img ) {
            $circle = '<span class="glow-cat-circle glow-cat-circle--sale"><span class="glow-cat-sale-badge">%</span></span>';
        } else {
            $circle = '<span class="glow-cat-circle">' . glow_cat_nav_image( (int) $value ) . '</span>';
        }
 
        return sprintf(
            '<a class="glow-cat-item%1$s" href="%2$s" data-filter="%3$s" data-value="%4$s"%5$s>%6$s<span class="glow-cat-label">%7$s</span></a>',
            $is_active ? ' is-active' : '',
            esc_url( $url ),
            esc_attr( $param ),
            esc_attr( $value ),
            $is_active ? ' aria-current="true"' : '',
            $circle,
            esc_html( $label )
        );
    }
}
 
add_shortcode( 'glow_shop_categories', function () {
    if ( ! function_exists( 'wc_get_page_id' )
        || ! function_exists( 'glow_shop_category_options' )
        || ! function_exists( 'glow_shop_scent_options' )
        || ! function_exists( 'glow_shop_current_filters' ) ) {
        return '';
    }
 
    $shop_id = wc_get_page_id( 'shop' );
    if ( $shop_id <= 0 ) {
        return '';
    }
 
    $shop_url = get_permalink( $shop_id );
    $current  = glow_shop_current_filters();
 
    static $assets_printed = false;
 
    ob_start();
    ?>
    <div class="glow-cat-nav">
        <div class="glow-cat-card glow-cat-card--categories">
            <div class="glow-cat-head">
                <h3 class="glow-cat-title">Shop By Category</h3>
                <a class="glow-cat-viewall" href="<?php echo esc_url( $shop_url ); ?>">View All</a>
            </div>
            <div class="glow-cat-items">
                <?php
                foreach ( glow_shop_category_options() as $label => $value ) {
                    echo glow_cat_nav_item( 'glow_category', $value, $label, (string) $value === $current['category'], $shop_url ); // phpcs:ignore
                }
                ?>
            </div>
        </div>
 
        <div class="glow-cat-card glow-cat-card--scents">
            <div class="glow-cat-head">
                <h3 class="glow-cat-title">Shop By Scent</h3>
                <a class="glow-cat-viewall" href="<?php echo esc_url( $shop_url ); ?>">View All</a>
            </div>
            <div class="glow-cat-items">
                <?php
                foreach ( glow_shop_scent_options() as $label => $id ) {
                    echo glow_cat_nav_item( 'glow_family', $id, $label, (int) $id === (int) $current['scent'], $shop_url ); // phpcs:ignore
                }
                ?>
            </div>
        </div>
    </div>
 
    <?php if ( ! $assets_printed ) : $assets_printed = true; ?>
    <style>
        .glow-cat-nav {
            --glow-pink: #e6197a;
            --glow-pink-soft: #fbe8ef;
            --glow-pink-line: #f6c9da;
            display: flex;
            gap: 16px;
            width: 100%;
            margin: 0 0 22px;
            box-sizing: border-box;
        }
        .glow-cat-nav *, .glow-cat-nav *::before, .glow-cat-nav *::after { box-sizing: border-box; }
        .glow-cat-card {
            min-width: 0;
            padding: 22px 26px 26px;
            background: rgba(255, 255, 255, .9);
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(120, 40, 70, .07);
        }
        .glow-cat-card--categories { flex: 2.15 1 0; }
        .glow-cat-card--scents { flex: 1 1 0; }
        .glow-cat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }
        .glow-cat-title {
            margin: 0;
            padding: 0;
            color: var(--glow-pink);
            font-size: 21px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: .01em;
            text-transform: uppercase;
        }
        .glow-cat-viewall {
            color: var(--glow-pink);
            font-size: 15px;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
            white-space: nowrap;
        }
        .glow-cat-viewall:hover { color: #b80f5c; }
        .glow-cat-items {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .glow-cat-item {
            flex: 1 1 0;
            min-width: 86px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #222;
            text-align: center;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
        }
        .glow-cat-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 84px;
            height: 84px;
            margin-bottom: 10px;
            overflow: hidden;
            background: var(--glow-pink-soft);
            border: 2px solid var(--glow-pink-line);
            border-radius: 50%;
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        .glow-cat-circle img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .glow-cat-circle--sale { background: var(--glow-pink-soft); }
        .glow-cat-sale-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: var(--glow-pink);
            border-radius: 50%;
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }
        .glow-cat-label {
            font-size: 14.5px;
            line-height: 1.35;
        }
        .glow-cat-item:hover .glow-cat-circle {
            transform: translateY(-2px);
            border-color: var(--glow-pink);
            box-shadow: 0 4px 10px rgba(230, 25, 122, .15);
        }
        .glow-cat-item.is-active .glow-cat-circle {
            border-color: var(--glow-pink);
            box-shadow: 0 0 0 3px rgba(230, 25, 122, .18);
        }
        .glow-cat-item.is-active .glow-cat-label {
            color: var(--glow-pink);
            font-weight: 600;
        }
        .glow-cat-item:focus-visible { outline: 2px solid var(--glow-pink); outline-offset: 3px; border-radius: 8px; }
 
        @media (max-width: 1024px) {
            .glow-cat-nav { flex-direction: column; }
        }
        @media (max-width: 767px) {
            .glow-cat-card { padding: 18px 14px 18px; }
            .glow-cat-title { font-size: 18px; }
            .glow-cat-label { font-size: 13.5px; }
            .glow-cat-items {
                justify-content: flex-start;
                gap: 10px;
                overflow-x: auto;
                padding-bottom: 4px;
                scroll-snap-type: x proximity;
                scrollbar-width: none;
            }
            .glow-cat-items::-webkit-scrollbar { display: none; }
            .glow-cat-item { flex: 0 0 92px; scroll-snap-align: start; }
            .glow-cat-circle { width: 72px; height: 72px; }
            .glow-cat-viewall{display:none;}
        }
    </style>
 
    <script>
    (function () {
        if (window.glowCatNavInit) { return; }
        window.glowCatNavInit = true;
 
        function getSelect(name) {
            return document.querySelector('.glow-shop-filters select[name="' + name + '"]');
        }
 
        function fire(select) {
            select.dispatchEvent(new Event('change', { bubbles: true }));
        }
 
        /* Highlight the icon that matches the current filter. */
        function syncActive() {
            var params = new URL(window.location.href).searchParams;
            var catSel = getSelect('glow_category');
            var scentSel = getSelect('glow_family');
            var values = {
                glow_category: catSel ? catSel.value : (params.get('glow_category') || ''),
                glow_family: scentSel ? scentSel.value : (params.get('glow_family') || '')
            };
            document.querySelectorAll('.glow-cat-item').forEach(function (item) {
                var on = values[item.getAttribute('data-filter')] === item.getAttribute('data-value');
                item.classList.toggle('is-active', on);
                if (on) { item.setAttribute('aria-current', 'true'); }
                else { item.removeAttribute('aria-current'); }
            });
        }
 
        document.addEventListener('click', function (e) {
            var link = e.target.closest('.glow-cat-item, .glow-cat-viewall');
            if (!link || !link.closest('.glow-cat-nav')) { return; }
 
            var catSel = getSelect('glow_category');
            var scentSel = getSelect('glow_family');
            /* No filter form on this page: let the link work normally. */
            if (!catSel || !scentSel) { return; }
 
            e.preventDefault();
 
            if (link.classList.contains('glow-cat-viewall')) {
                catSel.value = '';
                scentSel.value = '';
                fire(catSel);
            } else {
                var select = link.getAttribute('data-filter') === 'glow_category' ? catSel : scentSel;
                var value = link.classList.contains('is-active') ? '' : link.getAttribute('data-value');
                select.value = value;
                if (select.value !== value) {
                    /* Option missing from dropdown: fall back to normal navigation. */
                    window.location.href = link.href;
                    return;
                }
                fire(select);
            }
            syncActive();
        });
 
        /* Keep icons in sync when the dropdowns are used directly. */
        document.addEventListener('change', function (e) {
            if (e.target.closest('.glow-shop-filters select')) { setTimeout(syncActive, 0); }
        });
        if (window.jQuery) {
            window.jQuery(document.body).on('glow_filters_loaded', syncActive);
        }
        window.addEventListener('popstate', function () { setTimeout(syncActive, 0); });
    })();
    </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
} );
 
/* =====================================================================
 * 4. PAGINATION FIX V2
 * ===================================================================== */
 
/**
 * GLOW Pagination Fix v2 (Shop page / product archives)
 * REPLACES the previous "GLOW Pagination Fix" snippet: delete the old one completely.
 * Paste BELOW the other GLOW snippets (functions.php or Code Snippets).
 */
 
/* Number of products your product grid widget shows per page. */
if ( ! defined( 'GLOW_SHOP_PER_PAGE' ) ) {
    define( 'GLOW_SHOP_PER_PAGE', 12 );
}
 
/* Set to true only if pagination still fails: shows an admin-only debug box. */
if ( ! defined( 'GLOW_PAGINATION_DEBUG' ) ) {
    define( 'GLOW_PAGINATION_DEBUG', false );
}
 
if ( ! function_exists( 'glow_shop_requested_page' ) ) {
    function glow_shop_requested_page() {
        $page = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
        foreach ( array( 'paged', 'product-page' ) as $key ) {
            if ( isset( $_GET[ $key ] ) && is_scalar( $_GET[ $key ] ) ) {
                $page = max( $page, absint( $_GET[ $key ] ) );
            }
        }
        return $page;
    }
}
 
if ( ! function_exists( 'glow_is_shop_context' ) ) {
    function glow_is_shop_context() {
        if ( ! function_exists( 'is_shop' ) ) {
            return false;
        }
        $shop_id = wc_get_page_id( 'shop' );
        return is_shop() || is_product_taxonomy() || ( $shop_id > 0 && is_page( $shop_id ) );
    }
}
 
/* 1. WooCommerce's own shop query uses the same number per page as the grid,
      so the page count in the pagination matches and /page/2/ always exists. */