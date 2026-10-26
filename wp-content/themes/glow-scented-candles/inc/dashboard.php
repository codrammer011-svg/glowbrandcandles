<?php
/**
 * Dashboard – access shortcode, catalog seller field
 * Auto-split from functions.php – edit this file, not the monolith.
 */

/**
 * [glow_access_dashboard] — role-aware "Access Dashboard" button.
 *
 * Renders a button that sends the logged-in user to the dashboard for their role:
 *   representative -> /representative-dashboard/
 *   fundraiser     -> /fundraiser-dashboard/
 *   seller         -> /seller-dashboard/
 * Logged-out visitors are sent to the login page (returning here afterwards).
 *
 * Overridable attributes:
 *   [glow_access_dashboard label="Access Dashboard"
 *       fundraiser_url="/fundraiser-dashboard/"
 *       representative_url="/representative-dashboard/"
 *       seller_url="/seller-dashboard/"
 *       class="" ]
 */
add_shortcode('glow_access_dashboard', 'glow_access_dashboard_shortcode');
function glow_access_dashboard_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'label'              => 'Access Dashboard',
            'fundraiser_url'     => home_url('/fundraiser-dashboard/'),
            'representative_url' => home_url('/representative-dashboard/'),
            'seller_url'         => home_url('/seller-dashboard/'),
            'default_url'        => home_url('/my-account/'),
            'class'              => '',
        ),
        $atts,
        'glow_access_dashboard'
    );

    // Allow relative paths (e.g. "/fundraiser-dashboard/") in the attributes.
    $abs = static function ($url) {
        return ( strpos($url, 'http') === 0 ) ? $url : home_url('/' . ltrim($url, '/'));
    };

    if (! is_user_logged_in()) {
        $current = home_url(add_query_arg(array(), $GLOBALS['wp']->request ?? ''));
        $url     = wp_login_url($current);
    } else {
        $roles = (array) wp_get_current_user()->roles;
        if (in_array('representative', $roles, true)) {
            $url = $abs($atts['representative_url']);
        } elseif (in_array('fundraiser', $roles, true) || in_array('administrator', $roles, true)) {
            $url = $abs($atts['fundraiser_url']);
        } elseif (in_array('seller', $roles, true)) {
            $url = $abs($atts['seller_url']);
        } else {
            $url = $abs($atts['default_url']);
        }
    }

    // Print the button styles once per page.
    static $printed_style = false;
    $style = '';
    if (! $printed_style) {
        $printed_style = true;
        $style = '<style>
        .glow-access-dashboard-btn{display:inline-block;background:#22c993;color:#fff !important;
            padding:12px 30px;border-radius:8px;font-weight:600;font-size:16px;line-height:1.2;
            text-decoration:none;border:none;cursor:pointer;transition:background .2s ease;}
        .glow-access-dashboard-btn:hover{background:#1aa87b;color:#fff !important;}
        </style>';
    }

    $class = trim('glow-access-dashboard-btn ' . $atts['class']);

    return $style . sprintf(
        '<a href="%s" class="%s">%s</a>',
        esc_url($url),
        esc_attr($class),
        esc_html($atts['label'])
    );
}

/**
 * Catalog / brochure mass-order seller field.
 *
 * Shared by the catalogue templates (summer/winter/new). When a FUNDRAISER is
 * logged in, render a dropdown of the sellers registered under that fundraiser
 * (linked via user meta user_registration_seller_fundraiser = fundraiser ID),
 * so orders can be attributed to a specific seller. Everyone else keeps the
 * free-text "Seller Name" input. The element keeps id="nSeller" so the existing
 * catalogue JS ( $("#nSeller").val() ) works unchanged.
 */
function glow_render_catalog_seller_field()
{
    $preset = ! empty($_GET['seller-name']) ? sanitize_text_field(wp_unslash($_GET['seller-name'])) : '';

    $is_fundraiser = false;
    $fund_id       = 0;
    if ( is_user_logged_in() ) {
        $u = wp_get_current_user();
        if ( in_array('fundraiser', (array) $u->roles, true) ) {
            $is_fundraiser = true;
            $fund_id       = $u->ID;
        }
    }

    if ( $is_fundraiser ) {
        $sellers = get_users( array(
            'meta_key'   => 'user_registration_seller_fundraiser',
            'meta_value' => $fund_id,
            'orderby'    => 'display_name',
            'order'      => 'ASC',
        ) );

        $names = array();
        foreach ( $sellers as $s ) {
            $name = trim( get_user_meta($s->ID, 'first_name', true) . ' ' . get_user_meta($s->ID, 'last_name', true) );
            if ( '' === $name ) {
                $name = $s->display_name;
            }
            if ( '' !== $name ) {
                $names[] = $name;
            }
        }
        // A preset name that is not a registered seller opens in "Add Seller" mode.
        $custom = ( '' !== $preset && 'Catalog' !== $preset && ! in_array($preset, $names, true) ) ? $preset : '';

        $style = 'padding:6px 10px;border:1px solid #ccc;border-radius:3px;font-size:13px;background:#fff;';
        echo '<select ' . ( $custom ? '' : 'id="nSeller" name="mass-seller-name-new-test" class="mass-seller-name-new-test" ' ) . 'data-glow-seller-select style="' . $style . 'min-width:160px;">';
        echo '<option value="">— Select Seller —</option>';
        echo '<option value="Catalog"' . selected($preset, 'Catalog', false) . '>Catalog (group order)</option>';
        foreach ( $names as $name ) {
            echo '<option value="' . esc_attr($name) . '"' . selected($preset, $name, false) . '>' . esc_html($name) . '</option>';
        }
        echo '<option value="__glow_add_seller__"' . selected((bool) $custom, true, false) . '>+ Add Seller</option>';
        echo '</select>';
        // Seller not registered on the site: the typed name is saved on the order lines and
        // reported with the manually added sellers.
        echo '<input type="text" ' . ( $custom ? 'id="nSeller" name="mass-seller-name-new-test" class="mass-seller-name-new-test" ' : 'hidden ' ) . 'data-glow-new-seller value="' . esc_attr($custom) . '" placeholder="Enter seller name" aria-label="Seller name" maxlength="60" style="' . $style . 'width:160px;margin-left:6px;">';
        ?>
<script>
jQuery(function ($) {
    var $sel = $('select[data-glow-seller-select]'), $txt = $('input[data-glow-new-seller]');
    if (!$sel.length || !$txt.length) { return; }
    function sync(v) { $('.seller-buyer-cookie-form .mass-seller-name-new').val(v); }
    // Only the control in use carries the id, name and class the catalogue scripts read.
    function activate($on, $off) {
        $off.removeAttr('id name').removeClass('mass-seller-name-new-test');
        $on.attr({ id: 'nSeller', name: 'mass-seller-name-new-test' }).addClass('mass-seller-name-new-test');
    }
    $sel.on('change', function () {
        if ($sel.val() === '__glow_add_seller__') {
            activate($txt, $sel);
            $txt.prop('hidden', false).val('').trigger('focus');
        } else {
            activate($sel, $txt);
            $txt.prop('hidden', true).val('');
        }
        // After the catalogue's own change handler, which copies the raw select value.
        setTimeout(function () { sync($.trim($('#nSeller').val() || '')); }, 0);
    });
    $txt.on('input change', function () { sync($.trim($txt.val())); });
    if (!$txt.prop('hidden')) { sync($.trim($txt.val())); }
});
</script>
        <?php
    } else {
        echo '<input id="nSeller" type="text" name="mass-seller-name-new-test" value="' . esc_attr($preset) . '" class="mass-seller-name-new-test" placeholder="Seller Name" style="padding:6px 10px;border:1px solid #ccc;border-radius:3px;font-size:13px;width:140px;background:#fff;">';
    }
}

/**
 * Layout fix for the catalogue sticky sub-nav (category links vs seller bar overlap).
 */
add_action('wp_head', 'glow_catalog_subnav_css', 99);
function glow_catalog_subnav_css()
{
    ?>
<style id="glow-catalog-subnav-fix">
.catalog-subnav-sticky .container{flex-wrap:nowrap !important;align-items:center !important;}
.catalog-subnav-sticky #menu{flex-wrap:nowrap !important;overflow-x:auto !important;-webkit-overflow-scrolling:touch;
    position:static !important;height:auto !important;width:auto !important;left:auto !important;top:auto !important;
    min-width:0;flex:1 1 auto;}
.catalog-subnav-sticky #menu li,.catalog-subnav-sticky #menu li.list-items{position:static !important;float:none !important;
    white-space:nowrap;list-style:none !important;}
.catalog-subnav-sticky #menu a.bookmark{white-space:nowrap;}
.catalog-subnav-sticky #seller_cart_bar_container{flex:0 0 auto !important;flex-shrink:0 !important;position:static !important;}
@media (max-width:782px){
    .catalog-subnav-sticky .container{flex-wrap:wrap !important;}
    .catalog-subnav-sticky #seller_cart_bar_container{width:100%;justify-content:flex-start;}
}
</style>
    <?php
}