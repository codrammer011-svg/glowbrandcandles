<?php
/**
 * GLOW — Seller Brochure Orders
 */
defined( 'ABSPATH' ) || exit;

define( 'GLOW_SO_DB_VERSION', '1.0' );

/* =========================================================
   TABLES
   ========================================================= */

function glow_so_orders_table() {
    global $wpdb;
    return $wpdb->prefix . 'glow_seller_orders';
}

function glow_so_items_table() {
    global $wpdb;
    return $wpdb->prefix . 'glow_seller_order_items';
}

function glow_so_install_tables() {

    if ( get_option( 'glow_so_db_version' ) === GLOW_SO_DB_VERSION ) return;

    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $orders = glow_so_orders_table();
    $items  = glow_so_items_table();

    dbDelta( "CREATE TABLE {$orders} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        fundraiser_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        seller_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        seller_name VARCHAR(191) NOT NULL DEFAULT '',
        catalog VARCHAR(64) NOT NULL DEFAULT '',
        status VARCHAR(20) NOT NULL DEFAULT 'submitted',
        item_count INT(11) NOT NULL DEFAULT 0,
        order_total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        submitted_at DATETIME NULL DEFAULT NULL,
        money_received TINYINT(1) NOT NULL DEFAULT 0,
        money_confirmed_at DATETIME NULL DEFAULT NULL,
        moved_at DATETIME NULL DEFAULT NULL,
        wc_order_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY  (id),
        KEY fundraiser_status (fundraiser_id, status),
        KEY seller_status (seller_id, status)
    ) {$charset};" );

    dbDelta( "CREATE TABLE {$items} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        order_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        product_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        sku VARCHAR(100) NOT NULL DEFAULT '',
        product_name VARCHAR(191) NOT NULL DEFAULT '',
        qty INT(11) NOT NULL DEFAULT 0,
        unit_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        line_total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        PRIMARY KEY  (id),
        KEY order_id (order_id)
    ) {$charset};" );

    update_option( 'glow_so_db_version', GLOW_SO_DB_VERSION );
}
add_action( 'plugins_loaded', 'glow_so_install_tables' );


/* =========================================================
   PERMISSION HELPERS
   ========================================================= */

function glow_so_fundraiser_allows( $fid ) {
    if ( ! $fid ) return false;
    $val = get_user_meta( $fid, 'allow_seller_orders', true );
    if ( is_array( $val ) ) return ! empty( $val );
    return in_array( $val, array( 'yes', '1', 1 ), true );
}

function glow_so_seller_allows( $sid, $fid ) {
    $val = get_user_meta( $sid, 'glow_allow_brochure_order', true );
    if ( '' === $val ) return glow_so_fundraiser_allows( $fid );
    return 'yes' === $val;
}

function glow_so_get_fundraiser_id( $sid ) {
    $fid = get_user_meta( $sid, 'user_registration_seller_fundraiser', true );
    return $fid ? absint( $fid ) : 0;
}

function glow_so_fundraiser_is_open( $fid ) {
    return get_user_meta( $fid, 'user_action_status', true ) != 2;
}

/** Master gate */
function glow_so_seller_can_order( $sid = 0 ) {

    $sid  = $sid ? $sid : get_current_user_id();
    $user = get_userdata( $sid );

    if ( ! $user || ! in_array( 'seller', (array) $user->roles, true ) ) return false;

    $fid = glow_so_get_fundraiser_id( $sid );
    if ( ! $fid ) return false;

    if ( ! glow_so_fundraiser_is_open( $fid ) ) return false;

    return glow_so_seller_allows( $sid, $fid );
}


/* =========================================================
   FETCH HELPERS
   ========================================================= */

function glow_so_get_fundraiser_orders( $fid, $status = null ) {
    global $wpdb;
    $t = glow_so_orders_table();

    if ( $status ) {
        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$t} WHERE fundraiser_id = %d AND status = %s ORDER BY submitted_at DESC",
            $fid, $status
        ) );
    }

    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$t} WHERE fundraiser_id = %d ORDER BY submitted_at DESC",
        $fid
    ) );
}

function glow_so_get_seller_orders( $sid ) {
    global $wpdb;
    $t = glow_so_orders_table();

    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$t} WHERE seller_id = %d ORDER BY submitted_at DESC",
        $sid
    ) );
}

function glow_so_get_items( $order_id ) {
    global $wpdb;
    $t = glow_so_items_table();

    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$t} WHERE order_id = %d ORDER BY id ASC",
        $order_id
    ) );
}

function glow_so_catalog_label( $key ) {
    $map = array(
        'spring-summer'  => 'Spring Summer',
        'perfect-scents' => 'Perfect Scents',
        'fall-winter'    => 'Fall-Winter',
    );
    return isset( $map[ $key ] ) ? $map[ $key ] : ucfirst( str_replace( '-', ' ', $key ) );
}


/* =========================================================
   SUBMIT ORDER (seller)
   ========================================================= */

add_action( 'wp_ajax_glow_submit_order', 'glow_submit_order' );
function glow_submit_order() {

    check_ajax_referer( 'glow_submit_order', 'nonce' );

    $sid  = get_current_user_id();
    $user = get_userdata( $sid );

    if ( ! $user || ! in_array( 'seller', (array) $user->roles, true ) ) {
        wp_send_json_error( array( 'msg' => 'Not allowed' ) );
    }

    $fid = glow_so_get_fundraiser_id( $sid );

    if ( ! $fid ) {
        wp_send_json_error( array( 'msg' => 'No fundraiser linked to your account' ) );
    }

    if ( ! glow_so_fundraiser_is_open( $fid ) ) {
        wp_send_json_error( array( 'msg' => 'This fundraiser has been closed' ) );
    }

    if ( ! glow_so_seller_allows( $sid, $fid ) ) {
        wp_send_json_error( array( 'msg' => 'Ordering is not enabled for you' ) );
    }

    $catalog = isset( $_POST['catalog'] ) ? sanitize_key( $_POST['catalog'] ) : '';
    $items   = isset( $_POST['items'] ) ? (array) $_POST['items'] : array();

    /* ---- Seller name: popup se aaya to wahi, warna account se ---- */
    $name = isset( $_POST['seller_name'] )
            ? sanitize_text_field( wp_unslash( $_POST['seller_name'] ) )
            : '';

    if ( '' === $name ) {
        $name = trim( $user->first_name . ' ' . $user->last_name );
        if ( '' === $name ) $name = $user->display_name;
    }

    /* ---- Price hamesha server se ---- */
    $clean = array();
    $count = 0;
    $total = 0;

    foreach ( $items as $pid => $qty ) {

        $pid = absint( $pid );
        $qty = absint( $qty );
        if ( ! $pid || $qty < 1 ) continue;

        $product = wc_get_product( $pid );
        if ( ! $product ) continue;

        $price = (float) $product->get_regular_price();
        $line  = $price * $qty;

        $clean[] = array(
            'product_id'   => $pid,
            'sku'          => $product->get_sku(),
            'product_name' => $product->get_name(),
            'qty'          => $qty,
            'unit_price'   => $price,
            'line_total'   => $line,
        );

        $count += $qty;
        $total += $line;
    }

    if ( ! $clean ) {
        wp_send_json_error( array( 'msg' => 'No items selected' ) );
    }

    global $wpdb;

    $wpdb->insert( glow_so_orders_table(), array(
        'fundraiser_id' => $fid,
        'seller_id'     => $sid,
        'seller_name'   => $name,
        'catalog'       => $catalog,
        'status'        => 'submitted',
        'item_count'    => $count,
        'order_total'   => $total,
        'submitted_at'  => current_time( 'mysql' ),
    ) );

    $order_id = $wpdb->insert_id;

    if ( ! $order_id ) {
        wp_send_json_error( array( 'msg' => 'Could not save order' ) );
    }

    foreach ( $clean as $row ) {
        $row['order_id'] = $order_id;
        $wpdb->insert( glow_so_items_table(), $row );
    }

    /* Chairperson ko mail — background me, taaki seller ko wait na karna pade */
    if ( function_exists( 'as_schedule_single_action' ) ) {
        as_schedule_single_action(
            time(),
            'glow_so_new_order_mail',
            array( $order_id ),
            'glow-reminders'
        );
    } else {
        glow_so_notify_new_order( $order_id );
    }

    wp_send_json_success( array(
        'order_id' => $order_id,
        'count'    => $count,
        'total'    => number_format( $total, 2 ),
    ) );
}


/* =========================================================
   MONEY RECEIVED (chairperson)
   ========================================================= */

add_action( 'wp_ajax_glow_money_received', 'glow_money_received' );
function glow_money_received() {

    check_ajax_referer( 'glow_money_received', 'nonce' );

    global $wpdb;
    $t  = glow_so_orders_table();
    $id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;

    $order = $id ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$t} WHERE id = %d", $id ) ) : null;

    if ( ! $order ) {
        wp_send_json_error( array( 'msg' => 'Order not found' ) );
    }

    /* Sirf usi fundraiser ka order, ya admin */
    if ( (int) $order->fundraiser_id !== get_current_user_id()
         && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'msg' => 'Not allowed' ) );
    }

    /* Cart me ja chuka order lock */
    if ( in_array( $order->status, array( 'moved', 'placed' ), true ) ) {
        wp_send_json_error( array( 'msg' => 'Order already moved' ) );
    }

    $on = ! empty( $_POST['checked'] ) ? 1 : 0;

    $wpdb->update( $t, array(
        'money_received'     => $on,
        'money_confirmed_at' => $on ? current_time( 'mysql' ) : null,
        'status'             => $on ? 'confirmed' : 'submitted',
    ), array( 'id' => $id ) );

    wp_send_json_success();
}




/* =========================================================
   ORDER EDIT (chairperson)
   ========================================================= */

/** Order chairperson ka hai ya nahi */
function glow_so_can_edit( $order ) {
    if ( ! $order ) return false;
    if ( in_array( $order->status, array( 'moved', 'placed' ), true ) ) return false;
    return (int) $order->fundraiser_id === get_current_user_id()
           || current_user_can( 'manage_options' );
}

/** Order ke totals dobara ginno */
function glow_so_recalc( $order_id ) {
    global $wpdb;

    $row = $wpdb->get_row( $wpdb->prepare(
        "SELECT SUM(qty) AS c, SUM(line_total) AS t FROM " . glow_so_items_table() . " WHERE order_id = %d",
        $order_id
    ) );

    $wpdb->update( glow_so_orders_table(), array(
        'item_count'  => (int) $row->c,
        'order_total' => (float) $row->t,
    ), array( 'id' => $order_id ) );

    return array( 'count' => (int) $row->c, 'total' => (float) $row->t );
}

/* ---- Order load karo ---- */
add_action( 'wp_ajax_glow_get_order', 'glow_get_order' );
function glow_get_order() {

    check_ajax_referer( 'glow_order_edit', 'nonce' );

    global $wpdb;
    $id    = absint( $_POST['order_id'] );
    $order = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM " . glow_so_orders_table() . " WHERE id = %d", $id
    ) );

    if ( ! $order || ( (int) $order->fundraiser_id !== get_current_user_id()
                       && ! current_user_can( 'manage_options' ) ) ) {
        wp_send_json_error( array( 'msg' => 'Not allowed' ) );
    }

    $items = array();
    foreach ( glow_so_get_items( $id ) as $it ) {
        $items[] = array(
            'id'    => (int) $it->id,
            'pid'   => (int) $it->product_id,
            'sku'   => $it->sku,
            'name'  => $it->product_name,
            'qty'   => (int) $it->qty,
            'price' => (float) $it->unit_price,
        );
    }

    wp_send_json_success( array(
        'order_id'    => $id,
        'seller_name' => $order->seller_name,
        'catalog'     => glow_so_catalog_label( $order->catalog ),
        'locked'      => in_array( $order->status, array( 'moved', 'placed' ), true ) ? 1 : 0,
        'items'       => $items,
    ) );
}

/* ---- Order save karo ---- */
add_action( 'wp_ajax_glow_save_order', 'glow_save_order' );
function glow_save_order() {

    check_ajax_referer( 'glow_order_edit', 'nonce' );

    global $wpdb;
    $id    = absint( $_POST['order_id'] );
    $order = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM " . glow_so_orders_table() . " WHERE id = %d", $id
    ) );

    if ( ! glow_so_can_edit( $order ) ) {
        wp_send_json_error( array( 'msg' => 'This order can no longer be edited' ) );
    }

    $name  = sanitize_text_field( wp_unslash( $_POST['seller_name'] ) );
    $items = isset( $_POST['items'] ) ? (array) $_POST['items'] : array();

    /* Purane items hatao, naye daalo — price hamesha server se */
    $wpdb->delete( glow_so_items_table(), array( 'order_id' => $id ) );

    foreach ( $items as $pid => $qty ) {

        $pid = absint( $pid );
        $qty = absint( $qty );
        if ( ! $pid || $qty < 1 ) continue;

        $product = wc_get_product( $pid );
        if ( ! $product ) continue;

        $price = (float) $product->get_regular_price();

        $wpdb->insert( glow_so_items_table(), array(
            'order_id'     => $id,
            'product_id'   => $pid,
            'sku'          => $product->get_sku(),
            'product_name' => $product->get_name(),
            'qty'          => $qty,
            'unit_price'   => $price,
            'line_total'   => $price * $qty,
        ) );
    }

    $t = glow_so_recalc( $id );

    if ( $name ) {
        $wpdb->update( glow_so_orders_table(), array( 'seller_name' => $name ), array( 'id' => $id ) );
    }

    wp_send_json_success( array(
        'count' => $t['count'],
        'total' => number_format( $t['total'], 2 ),
    ) );
}

/* ---- Product search (add karne ke liye) ---- */
add_action( 'wp_ajax_glow_search_products', 'glow_search_products' );
function glow_search_products() {

    check_ajax_referer( 'glow_order_edit', 'nonce' );

    $term = sanitize_text_field( wp_unslash( $_POST['term'] ) );
    if ( strlen( $term ) < 2 ) wp_send_json_success( array() );

    $ids = wc_get_products( array(
        'status'  => 'publish',
        'limit'   => 20,
        's'       => $term,
        'return'  => 'ids',
    ) );

    /* SKU se bhi dhoondo */
    $by_sku = wc_get_product_id_by_sku( $term );
    if ( $by_sku ) array_unshift( $ids, $by_sku );

    $out = array();
    foreach ( array_unique( $ids ) as $pid ) {
        $p = wc_get_product( $pid );
        if ( ! $p ) continue;
        $out[] = array(
            'pid'   => $pid,
            'sku'   => $p->get_sku(),
            'name'  => $p->get_name(),
            'price' => (float) $p->get_regular_price(),
        );
    }

    wp_send_json_success( $out );
}

/* ---- Cart me bhejo ---- */
add_action( 'wp_ajax_glow_move_to_cart', 'glow_move_to_cart' );
function glow_move_to_cart() {

    check_ajax_referer( 'glow_order_edit', 'nonce' );

    global $wpdb;
    $id    = absint( $_POST['order_id'] );
    $order = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM " . glow_so_orders_table() . " WHERE id = %d", $id
    ) );

    if ( ! glow_so_can_edit( $order ) ) {
        wp_send_json_error( array( 'msg' => 'Order already moved' ) );
    }

    if ( ! $order->money_received ) {
        wp_send_json_error( array( 'msg' => 'Please confirm money received first' ) );
    }

    $items = glow_so_get_items( $id );
    if ( ! $items ) {
        wp_send_json_error( array( 'msg' => 'Order has no items' ) );
    }

    if ( ! WC()->cart ) {
        wp_send_json_error( array( 'msg' => 'Cart not available' ) );
    }

    $added = 0;
    foreach ( $items as $it ) {

        /* restore_saved_order flag se wdm_add_item_data cookie overwrite nahi karega */
        $ok = WC()->cart->add_to_cart( (int) $it->product_id, (int) $it->qty, 0, array(), array(
            'restore_saved_order'  => 'Yes',
            'wdm_seller_name'      => $order->seller_name,
            'wdm_seller_display'   => $order->seller_name,
            'glow_seller_order_id' => $id,
        ) );

        if ( $ok ) $added++;
    }

    if ( ! $added ) {
        wp_send_json_error( array( 'msg' => 'Nothing could be added to cart' ) );
    }

    $wpdb->update( glow_so_orders_table(), array(
        'status'   => 'moved',
        'moved_at' => current_time( 'mysql' ),
    ), array( 'id' => $id ) );

    wp_send_json_success( array( 'added' => $added ) );
}



/* =========================================================
   CHECKOUT — order meta set karo
   ========================================================= */

   add_action( 'woocommerce_checkout_update_order_meta', 'glow_so_save_order_meta', 20 );
   function glow_so_save_order_meta( $order_id ) {
   
       if ( ! WC()->cart ) return;
   
       $glow_ids = array();
       $names    = array();
   
       foreach ( WC()->cart->get_cart() as $item ) {
           if ( empty( $item['glow_seller_order_id'] ) ) continue;
   
           $glow_ids[] = absint( $item['glow_seller_order_id'] );
           if ( ! empty( $item['wdm_seller_name'] ) ) {
               $names[] = $item['wdm_seller_name'];
           }
       }
   
       if ( ! $glow_ids ) return;   /* normal order — chhed-chhad mat karo */
   
       $glow_ids = array_values( array_unique( $glow_ids ) );
       $names    = array_values( array_unique( $names ) );
   
       global $wpdb;
       $t = glow_so_orders_table();
   
       $fid = (int) $wpdb->get_var( $wpdb->prepare(
           "SELECT fundraiser_id FROM {$t} WHERE id = %d", $glow_ids[0]
       ) );
   
       /* Dashboard table inhi meta ko padhta hai */
       update_post_meta( $order_id, 'wc_checkbox_field',      'Yes' );
       update_post_meta( $order_id, 'wc_order_type',          'Catlog' );
       update_post_meta( $order_id, 'wc_seller_field',        'custom' );
       update_post_meta( $order_id, 'wc_seller_field_custom',
           count( $names ) > 1 ? implode( ', ', $names ) : ( $names ? $names[0] : '' ) );
   
       if ( $fid ) {
           update_post_meta( $order_id, 'fundraiser_field', $fid );
       }
   
       update_post_meta( $order_id, 'glow_seller_order_ids', implode( ',', $glow_ids ) );
   
       
       /* Ab ye orders placed ho gaye */
    foreach ( $glow_ids as $gid ) {

        $wpdb->update( $t, array(
            'status'      => 'placed',
            'wc_order_id' => $order_id,
        ), array( 'id' => $gid ) );

        /* Seller ko mail — background me */
        if ( function_exists( 'as_schedule_single_action' ) ) {
            as_schedule_single_action(
                time(),
                'glow_so_placed_mail',
                array( (int) $gid, (int) $order_id ),
                'glow-reminders'
            );
        } else {
            glow_so_notify_order_placed( $gid, $order_id );
        }
    }
}



   

/**
 * Seller ke WooCommerce orders — jo uske brochure orders se bane
 */
function glow_so_get_seller_wc_orders( $sid ) {

    global $wpdb;
    $t = glow_so_orders_table();

    $ids = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT wc_order_id FROM {$t}
         WHERE seller_id = %d AND wc_order_id > 0
         ORDER BY wc_order_id DESC",
        $sid
    ) );

    $out = array();
    foreach ( $ids as $oid ) {
        $o = wc_get_order( $oid );
        if ( $o ) $out[] = $o;
    }
    return $out;
}   




/* =========================================================
   NEW ORDER EMAIL (seller → chairperson)
   ========================================================= */

/**
 * Chairperson ko naye seller order ki suchna
 */
function glow_so_notify_new_order( $order_id ) {

    global $wpdb;

    $order = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM " . glow_so_orders_table() . " WHERE id = %d", $order_id
    ) );

    if ( ! $order ) return false;

    $chair = get_userdata( $order->fundraiser_id );
    if ( ! $chair || ! is_email( $chair->user_email ) ) return false;

    $chair_name = trim( $chair->first_name . ' ' . $chair->last_name );
    if ( '' === $chair_name ) $chair_name = $chair->display_name;

    $items = glow_so_get_items( $order_id );
    $link  = home_url( '/fundraiser-dashboard/' );

    $subject = sprintf( 'New brochure order from %s — %d items, $%s',
        $order->seller_name,
        (int) $order->item_count,
        number_format( $order->order_total, 2 )
    );

    ob_start();
    ?>
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#4a4a4a;line-height:1.6;max-width:600px;">

       <p>Hi <?php echo esc_html( $chair_name ); ?>,</p>

       <p>
          <b><?php echo esc_html( $order->seller_name ); ?></b> has submitted a brochure order
          from the <b><?php echo esc_html( glow_so_catalog_label( $order->catalog ) ); ?></b> catalog.
       </p>

       <table style="width:100%;border-collapse:collapse;margin:20px 0;">
          <tr style="background:#4a4a4a;color:#fff;">
             <th style="padding:8px;text-align:left;font-size:12px;">SKU</th>
             <th style="padding:8px;text-align:left;font-size:12px;">Product</th>
             <th style="padding:8px;text-align:center;font-size:12px;">Qty</th>
             <th style="padding:8px;text-align:right;font-size:12px;">Total</th>
          </tr>
          <?php foreach ( $items as $it ) : ?>
             <tr style="border-bottom:1px solid #eee;">
                <td style="padding:7px 8px;font-size:12px;color:#999;"><?php echo esc_html( $it->sku ); ?></td>
                <td style="padding:7px 8px;font-size:13px;"><?php echo esc_html( $it->product_name ); ?></td>
                <td style="padding:7px 8px;text-align:center;font-size:13px;"><?php echo (int) $it->qty; ?></td>
                <td style="padding:7px 8px;text-align:right;font-size:13px;">$<?php echo number_format( $it->line_total, 2 ); ?></td>
             </tr>
          <?php endforeach; ?>
          <tr style="border-top:2px solid #4a4a4a;">
             <td colspan="2" style="padding:9px 8px;font-weight:700;">Total</td>
             <td style="padding:9px 8px;text-align:center;font-weight:700;"><?php echo (int) $order->item_count; ?></td>
             <td style="padding:9px 8px;text-align:right;font-weight:700;">$<?php echo number_format( $order->order_total, 2 ); ?></td>
          </tr>
       </table>

       <p style="background:#fff8e6;border-left:4px solid #cda85c;padding:12px 14px;font-size:14px;">
          <b>Before you submit this order:</b> please collect the money from
          <?php echo esc_html( $order->seller_name ); ?> and tick the
          <b>Money Received</b> checkbox on your dashboard.
       </p>

       <p style="margin:26px 0;">
          <a href="<?php echo esc_url( $link ); ?>"
             style="background:#17c671;color:#fff;padding:13px 30px;border-radius:30px;
                    text-decoration:none;font-weight:700;display:inline-block;">
             VIEW ON MY DASHBOARD
          </a>
       </p>

       <p style="font-size:13px;color:#8c8c8c;">The Glow Team</p>

    </div>
    <?php
    $body = ob_get_clean();

    if ( function_exists( 'WC' ) && WC()->mailer() ) {
        $body = WC()->mailer()->wrap_message( $subject, $body );
    }

    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    /* Seller ko reply ja sake */
    $seller = get_userdata( $order->seller_id );
    if ( $seller && is_email( $seller->user_email ) ) {
        $headers[] = 'Reply-To: ' . $seller->user_email;
    }

    return wp_mail( $chair->user_email, $subject, $body, $headers );
}

/** Background job */
add_action( 'glow_so_new_order_mail', 'glow_so_notify_new_order', 10, 1 );




/* =========================================================
   ORDER PLACED EMAIL (chairperson → seller)
   ========================================================= */

/**
 * Seller ko batao ki uska order place ho gaya
 */
function glow_so_notify_order_placed( $glow_order_id, $wc_order_id ) {

    global $wpdb;

    $order = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM " . glow_so_orders_table() . " WHERE id = %d", $glow_order_id
    ) );

    if ( ! $order ) return false;

    $seller = get_userdata( $order->seller_id );
    if ( ! $seller || ! is_email( $seller->user_email ) ) return false;

    $name = trim( $seller->first_name . ' ' . $seller->last_name );
    if ( '' === $name ) $name = $seller->display_name;

    $fid = (int) $order->fundraiser_id;

    $org = get_user_meta( $fid, 'user_registration_name_of_school_business', true );
    if ( ! $org ) $org = get_user_meta( $fid, 'user_registration_input_company', true );

    $chair = trim(
        get_user_meta( $fid, 'first_name', true ) . ' ' .
        get_user_meta( $fid, 'last_name', true )
    );

    $items = glow_so_get_items( $glow_order_id );
    $link  = home_url( '/seller-dashboard/' );

    $subject = sprintf( 'Your brochure order has been placed — order #%d', $wc_order_id );

    ob_start();
    ?>
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#4a4a4a;line-height:1.6;max-width:600px;">

       <p>Hi <?php echo esc_html( $name ); ?>,</p>

       <p>
          Good news — your chairperson
          <?php if ( $chair ) : ?><b><?php echo esc_html( $chair ); ?></b><?php endif; ?>
          has submitted your brochure order
          <?php if ( $org ) : ?>for <b><?php echo esc_html( $org ); ?></b><?php endif; ?>.
       </p>

       <p style="background:#eefaf3;border-left:4px solid #17c671;padding:12px 14px;font-size:14px;">
          <b>Order #<?php echo esc_html( $wc_order_id ); ?></b><br>
          <?php echo (int) $order->item_count; ?> items ·
          $<?php echo number_format( $order->order_total, 2 ); ?>
       </p>

       <table style="width:100%;border-collapse:collapse;margin:20px 0;">
          <tr style="background:#4a4a4a;color:#fff;">
             <th style="padding:8px;text-align:left;font-size:12px;">SKU</th>
             <th style="padding:8px;text-align:left;font-size:12px;">Product</th>
             <th style="padding:8px;text-align:center;font-size:12px;">Qty</th>
             <th style="padding:8px;text-align:right;font-size:12px;">Total</th>
          </tr>
          <?php foreach ( $items as $it ) : ?>
             <tr style="border-bottom:1px solid #eee;">
                <td style="padding:7px 8px;font-size:12px;color:#999;"><?php echo esc_html( $it->sku ); ?></td>
                <td style="padding:7px 8px;font-size:13px;"><?php echo esc_html( $it->product_name ); ?></td>
                <td style="padding:7px 8px;text-align:center;font-size:13px;"><?php echo (int) $it->qty; ?></td>
                <td style="padding:7px 8px;text-align:right;font-size:13px;">$<?php echo number_format( $it->line_total, 2 ); ?></td>
             </tr>
          <?php endforeach; ?>
          <tr style="border-top:2px solid #4a4a4a;">
             <td colspan="2" style="padding:9px 8px;font-weight:700;">Total</td>
             <td style="padding:9px 8px;text-align:center;font-weight:700;"><?php echo (int) $order->item_count; ?></td>
             <td style="padding:9px 8px;text-align:right;font-weight:700;">$<?php echo number_format( $order->order_total, 2 ); ?></td>
          </tr>
       </table>

       <p style="font-size:14px;">
          You can track your order status and shipping information on your seller dashboard.
       </p>

       <p style="margin:26px 0;">
          <a href="<?php echo esc_url( $link ); ?>"
             style="background:#17c671;color:#fff;padding:13px 30px;border-radius:30px;
                    text-decoration:none;font-weight:700;display:inline-block;">
             VIEW MY DASHBOARD
          </a>
       </p>

       <p style="font-size:13px;color:#8c8c8c;">Thank you for selling with Glow!<br>The Glow Team</p>

    </div>
    <?php
    $body = ob_get_clean();

    if ( function_exists( 'WC' ) && WC()->mailer() ) {
        $body = WC()->mailer()->wrap_message( $subject, $body );
    }

    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    /* Reply chairperson ko jaye */
    $chair_user = get_userdata( $fid );
    if ( $chair_user && is_email( $chair_user->user_email ) ) {
        $headers[] = 'Reply-To: ' . $chair_user->user_email;
    }

    return wp_mail( $seller->user_email, $subject, $body, $headers );
}

/** Background job */
add_action( 'glow_so_placed_mail', 'glow_so_notify_order_placed', 10, 2 );


/* =========================================================
   SMS (Twilio)
   ========================================================= */

/** Seller ka phone number */
function glow_so_get_phone( $sid ) {

    $keys = array(
        'user_registration_phone_number',
        'seller_phone',
        'billing_phone',
    );

    foreach ( $keys as $k ) {
        $v = get_user_meta( $sid, $k, true );
        if ( $v ) return $v;
    }
    return '';
}

/** Number ko Twilio format me lao */
function glow_so_format_phone( $raw ) {

    $n = preg_replace( '/[^0-9]/', '', $raw );

    if ( strlen( $n ) === 10 ) $n = '1' . $n;          /* US */
    if ( strlen( $n ) < 11 || strlen( $n ) > 15 ) return '';

    return '+' . $n;
}

/** Twilio se SMS bhejo */
function glow_so_send_sms( $to_raw, $message ) {

    $sid   = defined( 'GLOW_TWILIO_SID' )   ? GLOW_TWILIO_SID   : '';
    $token = defined( 'GLOW_TWILIO_TOKEN' ) ? GLOW_TWILIO_TOKEN : '';
    $from  = defined( 'GLOW_TWILIO_FROM' )  ? GLOW_TWILIO_FROM  : '';

    if ( ! $sid || ! $token || ! $from ) {
        error_log( 'GLOW SMS: credentials missing in wp-config.php' );
        return false;
    }

    $to = glow_so_format_phone( $to_raw );
    if ( ! $to ) {
        error_log( 'GLOW SMS: bad number — ' . $to_raw );
        return false;
    }

    $res = wp_remote_post(
        "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json",
        array(
            'timeout' => 20,
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( $sid . ':' . $token ),
            ),
            'body' => array(
                'From' => $from,
                'To'   => $to,
                'Body' => $message,
            ),
        )
    );

    if ( is_wp_error( $res ) ) {
        error_log( 'GLOW SMS error: ' . $res->get_error_message() );
        return false;
    }

    $code = wp_remote_retrieve_response_code( $res );

    if ( 201 !== $code ) {
        error_log( 'GLOW SMS failed (' . $code . '): ' . wp_remote_retrieve_body( $res ) );
        return false;
    }

    return true;
}

/** Background job */
add_action( 'glow_so_sms_job', 'glow_so_sms_job', 10, 2 );
function glow_so_sms_job( $seller_id, $message ) {
    $phone = glow_so_get_phone( $seller_id );
    if ( $phone ) glow_so_send_sms( $phone, $message );
}