<?php
/**
 * Cart Shortcodes, CSS & Checkout hooks
 * Auto-split from functions.php – edit this file, not the monolith.
 */

/**
 * GLOW Cart Page (replaces the ShopLentor Pro cart widgets)
 *
 * Shortcodes (place them in your Elementor cart page columns):
 *   [glow_cart_items]   -> left column: products, remove button, qty (AJAX)
 *   [glow_cart_totals]  -> right column: Cart Totals, shipping, checkout + Apple/Google Pay/Link
 *   [glow_cart_coupon]  -> right column: Apply Coupon card (AJAX)
 *
 * Uses WooCommerce's own cart scripts, so remove / qty / coupon / shipping
 * all update without a page reload, and payment plugins (Stripe) keep working.
 * Paste into your child theme's functions.php or a Code Snippets PHP snippet.
 */

/* Runs once per page: shipping calculator, cart checks, totals (same as WooCommerce's cart shortcode). */
if ( ! function_exists( 'glow_cart_prepare' ) ) {
    function glow_cart_prepare() {
        static $done = false;

        if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
            return false;
        }
        if ( $done ) {
            return true;
        }
        $done = true;

        wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

        $nonce = wc_get_var( $_REQUEST['woocommerce-shipping-calculator-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // phpcs:ignore
        if ( ! empty( $_POST['calc_shipping'] )
            && ( wp_verify_nonce( $nonce, 'woocommerce-shipping-calculator' ) || wp_verify_nonce( $nonce, 'woocommerce-cart' ) )
            && class_exists( 'WC_Shortcode_Cart' ) ) {
            WC_Shortcode_Cart::calculate_shipping();
            WC()->cart->calculate_totals();
        }

        do_action( 'woocommerce_check_cart_items' );
        WC()->cart->calculate_totals();

        glow_cart_assets();
        return true;
    }
}

/* ---------------------------------------------------------------
 * [glow_cart_items]
 * ------------------------------------------------------------- */
add_shortcode( 'glow_cart_items', function () {
    if ( ! glow_cart_prepare() ) {
        return '';
    }

    ob_start();

    if ( WC()->cart->is_empty() ) {
        echo '<div class="woocommerce glow-cart-items glow-cart-empty">';
        wc_get_template( 'cart/cart-empty.php' );
        echo '</div>';
        return ob_get_clean();
    }
    ?>
    <div class="woocommerce glow-cart-items">
        <?php do_action( 'woocommerce_before_cart' ); ?>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="glow-cart-list woocommerce-cart-form__contents">
                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0
                        || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        continue;
                    }

                    $name      = $_product->get_name();
                    $permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
                    $row_class = apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key );

                    /* Price: current price + (on sale) regular price and % off. */
                    $price_html = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                    $sale_html  = '';
                    $regular    = (float) $_product->get_regular_price();
                    $current    = (float) $_product->get_price();
                    if ( $_product->is_on_sale() && $regular > 0 && $regular > $current ) {
                        $regular_display = 'incl' === WC()->cart->get_tax_price_display_mode()
                            ? wc_get_price_including_tax( $_product, array( 'price' => $regular ) )
                            : wc_get_price_excluding_tax( $_product, array( 'price' => $regular ) );
                        $percent   = rtrim( rtrim( number_format( ( $regular - $current ) / $regular * 100, 2, '.', '' ), '0' ), '.' );
                        $sale_html = '<del>' . wc_price( $regular_display ) . '</del><span class="glow-off">' . esc_html( $percent ) . '% off</span>';
                    }

                    /* Quantity */
                    if ( $_product->is_sold_individually() ) {
                        $min_qty = 1;
                        $max_qty = 1;
                    } else {
                        $min_qty = 0;
                        $max_qty = $_product->get_max_purchase_quantity();
                    }
                    $qty_html = woocommerce_quantity_input(
                        array(
                            'input_name'   => "cart[{$cart_item_key}][qty]",
                            'input_value'  => $cart_item['quantity'],
                            'max_value'    => $max_qty,
                            'min_value'    => $min_qty,
                            'product_name' => $name,
                        ),
                        $_product,
                        false
                    );
                    $qty_html = apply_filters( 'woocommerce_cart_item_quantity', $qty_html, $cart_item_key, $cart_item );

                    $remove = apply_filters(
                        'woocommerce_cart_item_remove_link',
                        sprintf(
                            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                            /* translators: %s: product name */
                            esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $name ) ) ),
                            esc_attr( $product_id ),
                            esc_attr( $_product->get_sku() )
                        ),
                        $cart_item_key
                    );
                    ?>
                    <div class="glow-cart-row woocommerce-cart-form__cart-item <?php echo esc_attr( $row_class ); ?>">
                        <div class="glow-cart-thumb">
                            <div class="product-remove"><?php echo $remove; // phpcs:ignore ?></div>
                            <?php
                            if ( $permalink ) {
                                printf( '<a href="%s">%s</a>', esc_url( $permalink ), $thumbnail ); // phpcs:ignore
                            } else {
                                echo $thumbnail; // phpcs:ignore
                            }
                            ?>
                        </div>

                        <div class="glow-cart-info">
                            <div class="glow-cart-name">
                                <?php
                                /* Product names use <br> for a line break: allow only that tag. */
                                $name_safe = wp_kses( html_entity_decode( $name, ENT_QUOTES, 'UTF-8' ), array( 'br' => array() ) );
                                $name_html = $permalink ? sprintf( '<a href="%s">%s</a>', esc_url( $permalink ), $name_safe ) : $name_safe;
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $name_html, $cart_item, $cart_item_key ) );
                                ?>
                            </div>
                            <?php
                            echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore
                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                            }
                            ?>
                            <div class="glow-cart-price">
                                <span class="glow-now"><?php echo $price_html; // phpcs:ignore ?></span>
                                <?php echo $sale_html; // phpcs:ignore ?>
                            </div>
                        </div>

                        <div class="glow-cart-qty">
                            <span class="glow-cart-qty-label">Qty:</span>
                            <?php echo $qty_html; // phpcs:ignore ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="glow-cart-actions">
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                <?php do_action( 'woocommerce_cart_actions' ); ?>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </div>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

        <?php do_action( 'woocommerce_after_cart' ); ?>
    </div>
    <?php
    return ob_get_clean();
} );

/* ---------------------------------------------------------------
 * [glow_cart_totals]
 * ------------------------------------------------------------- */
add_shortcode( 'glow_cart_totals', function () {
    if ( ! glow_cart_prepare() || WC()->cart->is_empty() ) {
        return '';
    }
    ob_start();
    echo '<div class="woocommerce glow-cart-card glow-cart-totals-card">';
    woocommerce_cart_totals();
    echo '</div>';
    return ob_get_clean();
} );

/* "Shipping" row label -> "Shipment" on the cart page. */
add_filter( 'woocommerce_shipping_package_name', function ( $name ) {
    if ( function_exists( 'is_cart' ) && is_cart() && __( 'Shipping', 'woocommerce' ) === $name ) {
        return 'Shipment';
    }
    return $name;
}, 20 );

/* ---------------------------------------------------------------
 * [glow_cart_coupon]
 * ------------------------------------------------------------- */
add_shortcode( 'glow_cart_coupon', function () {
    if ( ! glow_cart_prepare() || WC()->cart->is_empty() || ! wc_coupons_enabled() ) {
        return '';
    }
    ob_start();
    ?>
    <div class="glow-cart-card glow-coupon-card">
        <h3 class="glow-card-title">Apply Coupon</h3>
        <form class="glow-coupon-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <label for="glow_coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
            <input type="text" name="coupon_code" id="glow_coupon_code" class="input-text" placeholder="Coupon code" autocomplete="off">
            <button type="submit" name="apply_coupon" value="Apply coupon" class="glow-btn">Apply</button>
        </form>
        <div class="glow-coupon-notice" aria-live="polite"></div>
        <p class="glow-coupon-note">If you have a coupon code, please apply it below.</p>
    </div>
    <?php
    return ob_get_clean();
} );

/* ---------------------------------------------------------------
 * Styles + script (printed once)
 * ------------------------------------------------------------- */
if ( ! function_exists( 'glow_cart_assets' ) ) {
    function glow_cart_assets() {
        static $printed = false;
        if ( $printed ) {
            return;
        }
        $printed = true;

        add_action( 'wp_footer', 'glow_cart_print_assets', 100 );
    }
}

if ( ! function_exists( 'glow_cart_print_assets' ) ) {
    function glow_cart_print_assets() {
        ?>
        <style>
            .glow-cart-items, .glow-cart-card {
                --glow-pink: #e6197a;
                --glow-navy: #1f2d4d;
                --glow-grad: linear-gradient(90deg, #E91E63 0%, #F72585 25%, #C026D3 50%, #8B5CF6 75%, #E91E63 100%);
                box-sizing: border-box;
            }
            .glow-cart-items *, .glow-cart-card * { box-sizing: border-box; }

            /* ---------- Items card ---------- */
            .glow-cart-items .woocommerce-cart-form { margin: 0; }
            .glow-cart-list {
                padding-bottom: 20px;
                background: #fff;
                border: 1.5px solid #f3a9c6;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0px 0px 5px 0px #E91E63;
            }
            .glow-cart-row {
                display: grid;
                grid-template-columns: 90px minmax(0, 1fr) auto;
                align-items: center;
                gap: 20px;
                padding: 20px 24px;
                border-bottom: 1px solid #e3e3e3;
            }
            .glow-cart-thumb {
                position: relative;
                width: 90px;
                height: 90px;
                background: #fff;
                border: 1px solid #e6e6e6;
                border-radius: 4px;
            }
            .glow-cart-thumb > a, .glow-cart-thumb > img { display: block; width: 100%; height: 100%; }
            .glow-cart-thumb img {
                display: block;
                width: 100%;
                height: 100%;
                padding: 4px;
                object-fit: contain;
            }
            .glow-cart-thumb .product-remove {
                position: absolute;
                top: -8px;
                left: -8px;
                z-index: 2;
            }
            .glow-cart-items .product-remove a.remove {
                display: flex !important;
                align-items: center;
                justify-content: center;
                width: 21px !important;
                height: 21px !important;
                margin: 0;
                background: #7a3db8 !important;
                border-radius: 50%;
                color: #fff !important;
                font-size: 16px !important;
                font-weight: 700;
                line-height: 1 !important;
                text-decoration: none;
            }
            .glow-cart-items .product-remove a.remove:hover { background: #5b2a8e !important; }

            .glow-cart-name {
                margin: 0 0 6px;
                color: #1b1b1b;
                font-size: 17px;
                line-height: 1.35;
            }
            .glow-cart-name a { color: inherit; text-decoration: none; }
            .glow-cart-name a:hover { color: var(--glow-pink); }
            .glow-cart-info .variation, .glow-cart-info .backorder_notification { margin: 0 0 6px; font-size: 13px; }
            .glow-cart-price {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 8px;
            }
            .glow-cart-price .glow-now {
                color: var(--glow-navy);
                font-size: 21px;
                font-weight: 700;
            }
            .glow-cart-price del { color: #7a7a7a; font-size: 14px; }
            .glow-off {
                padding: 3px 8px;
                background: #fdd8df;
                border-radius: 3px;
                color: #e0365c;
                font-size: 12px;
            }

            .glow-cart-qty { display: flex; align-items: center; gap: 12px; }
            .glow-cart-qty-label { color: #333; font-size: 15px; }
            .glow-cart-qty .quantity { margin: 0 !important; }
            .glow-cart-qty input.qty {
                width: 56px;
                height: 36px;
                padding: 2px 6px;
                background: #eee;
                border: 1px solid #cfcfcf;
                border-radius: 3px;
                color: #333;
                font-size: 16px;
                text-align: center;
            }

            .glow-cart-actions { margin: 0; }
            .glow-cart-actions [name="update_cart"] { display: none !important; }

            /* ---------- Right column cards ---------- */
            .glow-cart-card {
                margin: 0 0 20px;
                padding: 28px 26px 30px;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0px 0px 5px 0px #7C3EAD;
            }
            .glow-cart-totals-card { border: 1.5px solid #d6b3ea; }
            .glow-coupon-card { border: 1.5px solid #a9dcc5;     box-shadow: 0px 0px 5px 0px #10B981; }

            .glow-cart-totals-card .cart_totals { float: none !important; width: 100% !important; margin: 0 !important; }
            .glow-cart-totals-card .cart_totals h2 {
                margin: 0 0 22px;
                color: #333;
                font-size: 32px;
                font-weight: 600;
                letter-spacing: .02em;
                text-align: center;
                text-transform: uppercase;
            }
            .glow-cart-totals-card table.shop_table {
                width: 100%;
                margin: 0 0 22px;
                background: transparent;
                border: 0 !important;
                border-collapse: collapse;
                border-radius: 0;
            }
            .glow-cart-totals-card table.shop_table tr { display: table-row !important; }
            .glow-cart-totals-card table.shop_table th,
            .glow-cart-totals-card table.shop_table td {
                display: table-cell !important;
                padding: 16px 14px !important;
                background: transparent;
                border: 0 !important;
                color: #333;
                font-size: 15px;
                line-height: 1.5;
                vertical-align: top;
            }
            .glow-cart-totals-card table.shop_table th { width: 38%; font-weight: 600; text-align: left; }
            .glow-cart-totals-card table.shop_table td { text-align: right !important; }
            .glow-cart-totals-card table.shop_table td::before { display: none !important; }
            .glow-cart-totals-card tr.cart-subtotal th, .glow-cart-totals-card tr.cart-subtotal td,
            .glow-cart-totals-card tr.order-total th, .glow-cart-totals-card tr.order-total td { background: #f4f4f4 !important; }
            .glow-cart-totals-card tr.order-total td strong { font-weight: 700; }
            .glow-cart-totals-card #shipping_method { margin: 0; padding: 0; list-style: none; }
            .glow-cart-totals-card #shipping_method li { margin: 0 0 4px; }
            .glow-cart-totals-card .woocommerce-shipping-destination,
            .glow-cart-totals-card .woocommerce-shipping-totals p { margin: 0 0 6px; font-size: 14px; }
            .glow-cart-totals-card .shipping-calculator-button {
                color: var(--glow-pink) !important;
                font-size: 14px;
                text-decoration: none;
            }
            .glow-cart-totals-card .shipping-calculator-form { margin-top: 10px; text-align: left; }
            .glow-cart-totals-card .shipping-calculator-form p { margin: 0 0 8px; padding: 0; }
            .glow-cart-totals-card .shipping-calculator-form .input-text,
            .glow-cart-totals-card .shipping-calculator-form select {
                width: 100%;
                height: 42px;
                padding: 6px 12px;
                border: 1px solid #ddd;
                border-radius: 8px;
                font-size: 14px;
            }
            .glow-cart-totals-card .shipping-calculator-form button[name="calc_shipping"] {
                width: 100%;
                height: 42px;
                padding: 0 16px;
                background: var(--glow-grad) !important;
                border: 0;
                border-radius: 999px;
                color: #fff !important;
                font-size: 15px;
                font-weight: 700;
            }

            .glow-cart-totals-card .wc-proceed-to-checkout {
                display: flex;
                flex-direction: column;
                gap: 14px;
                margin: 0;
                padding: 0 !important;
            }
            .glow-cart-totals-card .wc-proceed-to-checkout a.checkout-button {
                order: -1;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 54px;
                margin: 0 !important;
                padding: 0 24px;
                background: var(--glow-grad) !important;
                border: 0;
                border-radius: 999px;
                color: #fff !important;
                font-size: 18px;
                font-weight: 700;
                text-decoration: none;
                transition: filter .2s ease;
            }
            .glow-cart-totals-card .wc-proceed-to-checkout a.checkout-button:hover { filter: brightness(1.08); }
            #wc-stripe-express-checkout-button-separator,
            #wc-stripe-payment-request-button-separator { display: none !important; }

            .glow-card-title {
                margin: 0 0 22px;
                color: #1b1b1b;
                font-size: 22px;
                font-weight: 600;
                text-align: center;
                text-transform: uppercase;
            }
            .glow-coupon-form { display: flex; flex-direction: column; gap: 14px; margin: 0; }
            .glow-coupon-form input[name="coupon_code"] {
                width: 100%;
                height: 50px;
                padding: 8px 16px;
                background: #fff;
                border: 1px solid #d9d9d9;
                border-radius: 10px;
                font-size: 16px;
            }
            .glow-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 46px;
                padding: 0 20px;
                background: var(--glow-grad) !important;
                border: 0 !important;
                border-radius: 999px !important;
                color: #fff !important;
                font-size: 17px;
                font-weight: 700;
                cursor: pointer;
                transition: filter .2s ease;
            }
            .glow-btn:hover { filter: brightness(1.08); }
            .glow-coupon-notice { margin-top: 12px; font-size: 14px; }
            .glow-coupon-notice:empty { display: none; }
            .glow-coupon-notice .woocommerce-error,
            .glow-coupon-notice .woocommerce-message,
            .glow-coupon-notice .woocommerce-info { margin: 0; padding: 10px 12px; font-size: 14px; }
            .glow-coupon-note { margin: 14px 0 0; color: #555; font-size: 13px; text-align: center; }
            .glow-coupon-card.is-loading { opacity: .6; pointer-events: none; }

            /* ---------- Animated gradient + shine (Checkout, Apply, Calculate shipping) ---------- */
            .glow-cart-totals-card .wc-proceed-to-checkout a.checkout-button,
            .glow-coupon-card .glow-btn,
            .glow-cart-totals-card .shipping-calculator-form button[name="calc_shipping"] {
                position: relative;
                overflow: hidden;
                justify-content: center;
                align-items: center;
                border: none !important;
                border-radius: 14px !important;
                color: #fff !important;
                font-weight: 600;
                text-decoration: none;
                background: var(--glow-grad) !important;
                background-size: 300% 300% !important;
                animation: glowCheckoutGradient 8s ease infinite;
                transition: all .35s ease;
            }
            .glow-cart-totals-card .wc-proceed-to-checkout a.checkout-button::before,
            .glow-coupon-card .glow-btn::before,
            .glow-cart-totals-card .shipping-calculator-form button[name="calc_shipping"]::before {
                content: "";
                position: absolute;
                top: 0;
                left: -150%;
                width: 35%;
                height: 100%;
                background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .3), transparent);
                animation: glowShine 4s linear infinite;
                pointer-events: none;
            }
            .glow-cart-totals-card .wc-proceed-to-checkout a.checkout-button:hover,
            .glow-coupon-card .glow-btn:hover,
            .glow-cart-totals-card .shipping-calculator-form button[name="calc_shipping"]:hover {
                filter: none;
                transform: translateY(-2px);
                box-shadow: 0 15px 35px rgba(233, 30, 99, .35);
                animation-duration: 5s;
            }
            @keyframes glowCheckoutGradient {
                0%   { background-position: 0% 50%; }
                50%  { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            @keyframes glowShine {
                100% { left: 180%; }
            }
            /* Visitors who turned off animations in their device settings. */
            @media (prefers-reduced-motion: reduce) {
                .glow-cart-totals-card .wc-proceed-to-checkout a.checkout-button,
                .glow-coupon-card .glow-btn,
                .glow-cart-totals-card .shipping-calculator-form button[name="calc_shipping"] { animation: none; }
                .glow-cart-totals-card .wc-proceed-to-checkout a.checkout-button::before,
                .glow-coupon-card .glow-btn::before,
                .glow-cart-totals-card .shipping-calculator-form button[name="calc_shipping"]::before { display: none; }
            }

            @media (max-width: 480px) {
                .glow-cart-row { grid-template-columns: 74px minmax(0, 1fr); gap: 14px; padding: 16px; }
                .glow-cart-thumb { width: 74px; height: 74px; }
                .glow-cart-name { font-size: 15px; }
                .glow-cart-price .glow-now { font-size: 18px; }
                .glow-cart-totals-card .cart_totals h2 { font-size: 26px; }
                .glow-cart-card { padding: 22px 18px 24px; }
                .glow-cart-qty { grid-column: 2; }
            }
        </style>

        <script>
        (function ($) {
            if (!$ || window.glowCartInit) { return; }
            window.glowCartInit = true;

            /* Qty change -> update cart automatically (WooCommerce AJAX). */
            var timer = null;
            $(document.body).on('input change', '.glow-cart-items input.qty', function () {
                var $input = $(this);
                clearTimeout(timer);
                if ($input.val() === '') { return; }
                timer = setTimeout(function () {
                    $input.closest('form.woocommerce-cart-form')
                        .find(':input[name="update_cart"]')
                        .prop('disabled', false)
                        .trigger('click');
                }, 700);
            });

            /* Coupon -> WooCommerce AJAX, then refresh items + totals. */
            $(document.body).on('submit', '.glow-coupon-form', function (e) {
                if (typeof wc_cart_params === 'undefined') { return; } /* normal submit fallback */
                e.preventDefault();

                var $form = $(this);
                var $card = $form.closest('.glow-coupon-card');
                var $notice = $card.find('.glow-coupon-notice');
                var code = $.trim($form.find('[name="coupon_code"]').val());

                if (!code) {
                    $notice.html('<div class="woocommerce-error" role="alert">Please enter a coupon code.</div>');
                    return;
                }

                $card.addClass('is-loading');
                $.ajax({
                    type: 'POST',
                    url: wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                    data: { security: wc_cart_params.apply_coupon_nonce, coupon_code: code },
                    dataType: 'html'
                })
                .done(function (response) {
                    $notice.html(response);
                    if (response.indexOf('woocommerce-error') === -1 && response.indexOf('is-error') === -1) {
                        $form.find('[name="coupon_code"]').val('');
                    }
                    $(document.body).trigger('applied_coupon', [code]);
                    $(document.body).trigger('wc_update_cart', [true]);
                })
                .fail(function () {
                    $notice.html('<div class="woocommerce-error" role="alert">Coupon could not be applied. Please try again.</div>');
                })
                .always(function () {
                    $card.removeClass('is-loading');
                });
            });

            /* Last item removed -> hide totals and coupon cards. */
            function hideWhenEmpty() {
                if (!$('.woocommerce-cart-form').length) {
                    $('.glow-cart-totals-card, .glow-coupon-card').remove();
                }
            }
            $(document.body).on('wc_cart_emptied updated_wc_div', hideWhenEmpty);
        })(window.jQuery);
        </script>
        <?php
    }
}

/* ============================================================================
 * GLOW Checkout redesign — visuals + order-summary extras (is_checkout only).
 * Pairs with template-glow-checkout.php + css/checkout-redesign.css.
 * Fields and behaviour are unchanged; this only adds styling, product
 * thumbnails, a coupon card and trust badges.
 * ========================================================================== */

// 1) Register the redesign stylesheet. It is ENQUEUED from woocommerce_before_checkout_form
//    (below) so it loads on ANY page that renders the [woocommerce_checkout] shortcode,
//    not just the designated checkout page.
add_action( 'wp_enqueue_scripts', function () {
	wp_register_style(
		'glow-checkout-redesign',
		get_stylesheet_directory_uri() . '/css/checkout-redesign.css',
		array(),
		'1.1.3'
	);
}, 20 );

// 1b) Checkout page heading ("Checkout" + item count). Added via hook because the
//     page is Elementor-built and bypasses the PHP template's own header markup.
add_action( 'woocommerce_before_checkout_form', function () {
	wp_enqueue_style( 'glow-checkout-redesign' );
	$count = ( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	echo '<div class="glow-checkout-head"><span class="glow-ch-icon">&#128722;</span><h1>' . esc_html__( 'Checkout', 'woocommerce' ) . '</h1>';
	if ( $count > 0 ) {
		echo '<span class="glow-ch-count">' . (int) $count . ' ' . esc_html( _n( 'ITEM', 'ITEMS', $count, 'woocommerce' ) ) . '</span>';
	}
	echo '</div>';
}, 5 );

// 1c) Render the "Order Summary" title INSIDE the order-review card (the standalone
//     #order_review_heading is hidden via CSS so the title sits within the card).
add_action( 'woocommerce_checkout_order_review', function () {
	echo '<h3 class="glow-os-title">&#127991;&#65039; ' . esc_html__( 'Order Summary', 'woocommerce' ) . '</h3>';
}, 5 );

// 2) Rename the order-review heading "Your order" -> "Order Summary".
add_filter( 'gettext', function ( $translated, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'Your order' === $text ) {
		return 'Order Summary';
	}
	return $translated;
}, 20, 3 );

// 3) Prepend a product thumbnail to each order-review line item (scoped to the
//    checkout order review only, so the cart page / mini-cart are untouched).
add_action( 'woocommerce_review_order_before_cart_contents', function () { $GLOBALS['glow_in_review'] = true; } );
add_action( 'woocommerce_review_order_after_cart_contents', function () { $GLOBALS['glow_in_review'] = false; } );
add_filter( 'woocommerce_cart_item_name', function ( $name, $cart_item, $cart_item_key ) {
	if ( is_admin() || empty( $GLOBALS['glow_in_review'] ) ) {
		return $name;
	}
	$product = isset( $cart_item['data'] ) ? $cart_item['data'] : null;
	if ( $product && is_a( $product, 'WC_Product' ) ) {
		$img = $product->get_image( array( 62, 62 ), array( 'class' => 'glow-thumb' ) );
		if ( $img ) {
			return $img . '<span class="glow-name-text">' . $name . '</span>';
		}
	}
	return $name;
}, 10, 3 );

// 4) Coupon card + trust badges as SEPARATE cards BELOW the order summary (not
//    inside #order_review — that caused the nested/double border + button overlap).
add_action( 'woocommerce_checkout_after_order_review', function () {
	echo '<div class="glow-summary-extras">';
	if ( function_exists( 'wc_coupons_enabled' ) && wc_coupons_enabled() ) {
		?>
		<div class="glow-coupon-card" id="glow-coupon-card">
			<h4><?php esc_html_e( 'APPLY COUPON', 'woocommerce' ); ?></h4>
			<div class="glow-coupon-row">
				<input type="text" id="glow_coupon_code" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
				<button type="button" class="button" id="glow_apply_coupon"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
			</div>
			<p class="glow-coupon-help">If you have a coupon code, please apply it below.</p>
		</div>
		<?php
	}
	?>
	<div class="glow-trust-card">
		<div class="glow-trust-row"><span class="glow-trust-ico">&#128737;</span><div><b>SECURE CHECKOUT</b><span>Your payment information is encrypted &amp; safe.</span></div></div>
		<div class="glow-trust-row"><span class="glow-trust-ico">&#128666;</span><div><b>FAST SHIPPING</b><span>Quick &amp; reliable delivery to your door.</span></div></div>
		<div class="glow-trust-row"><span class="glow-trust-ico">&#8617;</span><div><b>30-DAY RETURNS</b><span>Love it or return it &mdash; hassle-free.</span></div></div>
	</div>
	<?php
	echo '</div>';
} );

// 5) Apply coupon via the native WC AJAX (a <form> can't be nested inside the checkout form).
add_action( 'woocommerce_after_checkout_form', function () {
	if ( ! wc_coupons_enabled() ) {
		return;
	}
	?>
	<script>
	( function ( $ ) {
		$( document ).on( 'click', '#glow_apply_coupon', function () {
			var code = $.trim( $( '#glow_coupon_code' ).val() );
			if ( ! code || typeof wc_checkout_params === 'undefined' ) { return; }
			var $btn = $( this ).prop( 'disabled', true );
			$.ajax( {
				type: 'POST',
				url: wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
				data: { security: wc_checkout_params.apply_coupon_nonce, coupon_code: code },
				dataType: 'html',
				complete: function ( xhr ) {
					$( '.glow-coupon-card' ).prevAll( '.woocommerce-error, .woocommerce-message' ).remove();
					if ( xhr.responseText ) { $( '#glow-coupon-card' ).before( xhr.responseText ); }
					$( document.body ).trigger( 'update_checkout' );
					$btn.prop( 'disabled', false );
				}
			} );
		} );
		$( document ).on( 'keypress', '#glow_coupon_code', function ( e ) {
			if ( e.which === 13 ) { e.preventDefault(); $( '#glow_apply_coupon' ).click(); }
		} );
	} )( jQuery );
	</script>
	<?php
} );

require_once get_stylesheet_directory() . '/seller-share-popup.php';

/* =====================================================================
 * 7. CATEGORY-WISE PRODUCT ORDER (no ACF)
 *    Product edit screen: a "Category Display Order" box with one number
 *    field per category the product is in, labelled with the category name.
 *    1 = shows first in that category. Empty = after the numbered products,
 *    sorted by best sellers. Customer's Price sort still overrides this.
 *    Paste BELOW all the other GLOW shop code.
 * ===================================================================== */

if ( ! defined( 'GLOW_CAT_ORDER_PREFIX' ) ) {
    define( 'GLOW_CAT_ORDER_PREFIX', '_glow_cat_order_' );
}

/* ---------- Admin: meta box on the product edit screen ---------- */
