<?php
/**
 * Login design, seller register shortcuts
 * Auto-split from functions.php – edit this file, not the monolith.
 */

add_shortcode('seller_register', 'seller_register_shortcode');

function seller_register_shortcode()
{

    ob_start();

    include get_theme_file_path('/seller-register-content.php');

    return ob_get_clean();
}

add_shortcode('glow_seller_register_form', 'glow_seller_register_form_shortcode');

function glow_seller_register_form_shortcode()
{
    ob_start();
    include get_theme_file_path('/template-seller-register-form.php');
    return ob_get_clean();
}

add_shortcode('cart_items', function () {
    if (!WC()->cart) {
        return '0 ITEMS';
    }

    $count = WC()->cart->get_cart_contents_count();

    return $count . ' ' . ($count == 1 ? 'ITEMS' : 'ITEMS');
});

// wp-admin login page design Start//

function custom_login_logo()
{
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
    if (!$logo_url) {
        $logo_url = get_stylesheet_directory_uri() . '/screenshot.png';
    }
    ?>
    <style type="text/css">
        #login h1 a {
            background-image: url('<?php echo esc_url($logo_url); ?>');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            height: 100px;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'custom_login_logo');

function custom_login_logo_url()
{
    return home_url();
}
add_filter('login_headerurl', 'custom_login_logo_url');

function custom_login_logo_title()
{
    return get_bloginfo('name');
}
add_filter('login_headertext', 'custom_login_logo_title');

function custom_wp_login_design()
{
    ?>
    <style>
        body.login {
            background: #f5f5f5;
        }

        #login {
            width: 400px;
        }

        .login h1 a {
            background-image: url('YOUR-LOGO-URL');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            height: 100px;
        }

        .login form {
            background: #ffffff;
            border: none;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 0px 10px 0px #E91E63 !important;
        }

        .login label {
            font-size: 14px;
            font-weight: 600;
        }

        .login input[type="text"],
        .login input[type="password"] {
            border: 1px solid #ddd;
            border-radius: 14px;
            padding: 12px;
            height: 48px;
        }

        body.login #wp-submit {
            margin-top: 5px !important;
            width: 100%;
            position: relative;
            overflow: hidden;
            justify-content: center;
            align-items: center;
            border: none;
            border-radius: 14px;
            color: #fff !important;
            font-weight: 600;

            background: linear-gradient(90deg,
                    #E91E63 0%,
                    #F72585 25%,
                    #C026D3 50%,
                    #8B5CF6 75%,
                    #E91E63 100%);

            background-size: 300% 300%;
            animation: checkoutGradient 8s ease infinite;
            transition: all .35s ease;
        }

        body.login #wp-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(233, 30, 99, .35);
            animation-duration: 5s;
        }

        @keyframes checkoutGradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'custom_wp_login_design');

add_filter('terms_clauses', function ($clauses, $taxonomies, $args) {
    if (!in_array('product_cat', $taxonomies, true)) {
        return $clauses;
    }

    global $wpdb;

    $clauses['join'] .= " LEFT JOIN {$wpdb->termmeta} AS tm_order ON tm_order.term_id = t.term_id AND tm_order.meta_key = 'order'";
    $clauses['orderby'] = "ORDER BY CAST(COALESCE(tm_order.meta_value, 0) AS SIGNED)";
    $clauses['order'] = 'ASC';

    return $clauses;
}, 10, 3);
// wp-admin login page design end//
