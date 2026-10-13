<?php
/**
 * Import / Export for the whole fundraiser dataset:
 *   - cpts    : fundraiser, feedback, close_out, payment_history (posts + meta)
 *   - orders  : WooCommerce orders linked to a fundraiser (meta "fundraiser_field")
 *   - sellers : users linked to a fundraiser (meta "user_registration_seller_fundraiser")
 *
 * JSON = full-fidelity backup/restore (all datasets bundled).
 * CSV  = spreadsheet, one file per dataset (zipped if several are selected).
 *
 * Limitations (import): order line items require the referenced products to
 * exist; user passwords are never exported (imported users get a random pass).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const GLOW_FC_ORDER_LINK_META  = 'fundraiser_field';
const GLOW_FC_SELLER_LINK_META = 'user_registration_seller_fundraiser';

/** CPT post types this tool handles. */
function glow_fc_ie_post_types() {
	return array( 'fundraiser', 'feedback', 'close_out', 'payment_history' );
}

function glow_fc_ie_wc_active() {
	return function_exists( 'wc_get_orders' ) && function_exists( 'wc_create_order' );
}

/** Is WooCommerce using the HPOS (custom orders table) datastore? */
function glow_fc_ie_hpos_active() {
	return class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' )
		&& function_exists( 'wc_get_container' )
		&& \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
}

/**
 * Find order IDs by a meta key (optionally value), working on BOTH the HPOS and
 * legacy (post-based) datastores. wc_get_orders( meta_query ) is NOT supported
 * on the legacy datastore, so we query the meta tables directly.
 *
 * @return int[] order IDs
 */
function glow_fc_ie_find_order_ids_by_meta( $key, $value = null ) {
	global $wpdb;
	if ( ! glow_fc_ie_wc_active() ) {
		return array();
	}
	if ( glow_fc_ie_hpos_active() ) {
		$table = $wpdb->prefix . 'wc_orders_meta';
		$ids   = ( null === $value )
			? $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT order_id FROM {$table} WHERE meta_key = %s", $key ) )
			: $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT order_id FROM {$table} WHERE meta_key = %s AND meta_value = %s", $key, (string) $value ) );
	} else {
		$ids = ( null === $value )
			? $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT pm.post_id FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = %s AND p.post_type = 'shop_order'", $key ) )
			: $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT pm.post_id FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = %s AND pm.meta_value = %s AND p.post_type = 'shop_order'", $key, (string) $value ) );
	}
	return array_map( 'intval', (array) $ids );
}

/* --------------------------------------------------------------------------
 * Admin menu
 * ---------------------------------------------------------------------- */
add_action( 'admin_menu', function () {
	add_menu_page(
		__( 'Fundraiser Data', 'glow-fundraiser-core' ),
		__( 'Fundraiser Data', 'glow-fundraiser-core' ),
		'manage_options',
		'glow-fc-data',
		'glow_fc_ie_render_page',
		'dashicons-database-import',
		58
	);
} );

/* --------------------------------------------------------------------------
 * Collectors (export)
 * ---------------------------------------------------------------------- */

/** CPT posts + meta. */
function glow_fc_ie_collect_cpts( array $types ) {
	$types = array_values( array_intersect( $types, glow_fc_ie_post_types() ) );
	if ( empty( $types ) ) {
		return array();
	}
	$posts = get_posts( array(
		'post_type'   => $types,
		'post_status' => 'any',
		'numberposts' => -1,
		'orderby'     => 'ID',
		'order'       => 'ASC',
	) );
	$out = array();
	foreach ( $posts as $p ) {
		$meta = array();
		foreach ( (array) get_post_custom_keys( $p->ID ) as $key ) {
			$meta[ $key ] = get_post_meta( $p->ID, $key, false );
		}
		$out[] = array(
			'original_id'  => (int) $p->ID,
			'post_type'    => $p->post_type,
			'post_title'   => $p->post_title,
			'post_name'    => $p->post_name,
			'post_status'  => $p->post_status,
			'post_date'    => $p->post_date,
			'post_excerpt' => $p->post_excerpt,
			'post_content' => $p->post_content,
			'menu_order'   => (int) $p->menu_order,
			'post_parent'  => (int) $p->post_parent,
			'meta'         => $meta,
		);
	}
	return $out;
}

/** Build the export array for a single WC order object. */
function glow_fc_ie_order_item( $o ) {
	$items = array();
	foreach ( $o->get_items() as $item ) {
		$im = array();
		foreach ( $item->get_meta_data() as $m ) {
			$d = $m->get_data();
			$im[ $d['key'] ][] = $d['value'];
		}
		$items[] = array(
			'name'         => $item->get_name(),
			'product_id'   => $item->get_product_id(),
			'variation_id' => $item->get_variation_id(),
			'qty'          => $item->get_quantity(),
			'subtotal'     => $item->get_subtotal(),
			'total'        => $item->get_total(),
			'meta'         => $im,
		);
	}
	$meta = array();
	foreach ( $o->get_meta_data() as $m ) {
		$d = $m->get_data();
		$meta[ $d['key'] ][] = $d['value'];
	}
	$created = $o->get_date_created();
	return array(
		'original_id'  => (int) $o->get_id(),
		'status'       => $o->get_status(),
		'currency'     => $o->get_currency(),
		'date_created' => $created ? $created->date( 'c' ) : '',
		'customer_id'  => $o->get_customer_id(),
		'total'        => $o->get_total(),
		'billing'      => $o->get_address( 'billing' ),
		'shipping'     => $o->get_address( 'shipping' ),
		'items'        => $items,
		'meta'         => $meta,
	);
}

/** WooCommerce orders linked to a fundraiser (loads all — use only for CSV). */
function glow_fc_ie_collect_orders() {
	if ( ! glow_fc_ie_wc_active() ) {
		return array();
	}
	$out = array();
	foreach ( glow_fc_ie_find_order_ids_by_meta( GLOW_FC_ORDER_LINK_META ) as $id ) {
		$o = wc_get_order( $id );
		if ( $o ) {
			$out[] = glow_fc_ie_order_item( $o );
			unset( $o );
		}
	}
	return $out;
}

/** Users linked to a fundraiser (sellers / reps). */
function glow_fc_ie_collect_sellers() {
	$ids = get_users( array(
		'meta_key'     => GLOW_FC_SELLER_LINK_META,
		'meta_compare' => 'EXISTS',
		'fields'       => 'ID',
	) );
	$out = array();
	foreach ( (array) $ids as $uid ) {
		$u = get_userdata( $uid );
		if ( ! $u ) {
			continue;
		}
		$meta = array();
		foreach ( (array) get_user_meta( $uid ) as $key => $vals ) {
			if ( in_array( $key, array( 'session_tokens', 'user_pass' ), true ) ) {
				continue;
			}
			$meta[ $key ] = array_map( 'maybe_unserialize', (array) $vals );
		}
		$out[] = array(
			'original_id'     => (int) $uid,
			'user_login'      => $u->user_login,
			'user_email'      => $u->user_email,
			'first_name'      => $u->first_name,
			'last_name'       => $u->last_name,
			'display_name'    => $u->display_name,
			'user_registered' => $u->user_registered,
			'roles'           => array_values( (array) $u->roles ),
			'meta'            => $meta,
		);
	}
	return $out;
}

/* --------------------------------------------------------------------------
 * CSV helpers
 * ---------------------------------------------------------------------- */

/** Build a CSV string from columns + associative rows. */
function glow_fc_ie_csv_string( array $columns, array $rows ) {
	$fh = fopen( 'php://temp', 'r+' );
	fputcsv( $fh, $columns );
	foreach ( $rows as $row ) {
		$line = array();
		foreach ( $columns as $c ) {
			$v      = $row[ $c ] ?? '';
			$line[] = is_scalar( $v ) ? $v : wp_json_encode( $v );
		}
		fputcsv( $fh, $line );
	}
	rewind( $fh );
	$out = stream_get_contents( $fh );
	fclose( $fh );
	return $out;
}

/** Turn a collected dataset into [columns, rows] for CSV. */
function glow_fc_ie_dataset_to_csv( $dataset, array $items ) {
	if ( 'cpts' === $dataset ) {
		$core = array( 'original_id', 'post_type', 'post_title', 'post_name', 'post_status', 'post_date', 'post_excerpt', 'post_content', 'menu_order', 'post_parent' );
	} elseif ( 'orders' === $dataset ) {
		$core = array( 'original_id', 'status', 'currency', 'date_created', 'customer_id', 'total', 'billing', 'shipping', 'items' );
	} else { // sellers
		$core = array( 'original_id', 'user_login', 'user_email', 'first_name', 'last_name', 'display_name', 'user_registered', 'roles' );
	}
	$meta_keys = array();
	foreach ( $items as $it ) {
		foreach ( array_keys( $it['meta'] ?? array() ) as $k ) {
			$meta_keys[ $k ] = true;
		}
	}
	$meta_keys = array_keys( $meta_keys );
	sort( $meta_keys );
	$columns = array_merge( $core, array_map( function ( $k ) { return 'meta:' . $k; }, $meta_keys ) );

	$rows = array();
	foreach ( $items as $it ) {
		$row = array();
		foreach ( $core as $c ) {
			$row[ $c ] = $it[ $c ] ?? '';
		}
		foreach ( $meta_keys as $k ) {
			$vals = $it['meta'][ $k ] ?? '';
			$row[ 'meta:' . $k ] = ( is_array( $vals ) && count( $vals ) === 1 && is_scalar( $vals[0] ) ) ? $vals[0] : $vals;
		}
		$rows[] = $row;
	}
	return array( $columns, $rows );
}

/* --------------------------------------------------------------------------
 * Streaming JSON writers (memory-safe: one record at a time)
 * ---------------------------------------------------------------------- */
function glow_fc_ie_stream_json_cpts( array $types ) {
	$types = array_values( array_intersect( $types, glow_fc_ie_post_types() ) );
	if ( empty( $types ) ) {
		return;
	}
	$ids = get_posts( array( 'post_type' => $types, 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'orderby' => 'ID', 'order' => 'ASC' ) );
	$first = true;
	foreach ( $ids as $pid ) {
		$p = get_post( $pid );
		if ( ! $p ) {
			continue;
		}
		$meta = array();
		foreach ( (array) get_post_custom_keys( $pid ) as $key ) {
			$meta[ $key ] = get_post_meta( $pid, $key, false );
		}
		$item = array(
			'original_id'  => (int) $p->ID,
			'post_type'    => $p->post_type,
			'post_title'   => $p->post_title,
			'post_name'    => $p->post_name,
			'post_status'  => $p->post_status,
			'post_date'    => $p->post_date,
			'post_excerpt' => $p->post_excerpt,
			'post_content' => $p->post_content,
			'menu_order'   => (int) $p->menu_order,
			'post_parent'  => (int) $p->post_parent,
			'meta'         => $meta,
		);
		echo ( $first ? '' : ',' ) . wp_json_encode( $item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		$first = false;
		unset( $p, $meta, $item );
	}
}

function glow_fc_ie_stream_json_orders() {
	if ( ! glow_fc_ie_wc_active() ) {
		return;
	}
	$first = true;
	foreach ( glow_fc_ie_find_order_ids_by_meta( GLOW_FC_ORDER_LINK_META ) as $id ) {
		$o = wc_get_order( $id );
		if ( ! $o ) {
			continue;
		}
		echo ( $first ? '' : ',' ) . wp_json_encode( glow_fc_ie_order_item( $o ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		$first = false;
		unset( $o );
	}
}

function glow_fc_ie_stream_json_sellers() {
	$first = true;
	foreach ( glow_fc_ie_collect_sellers() as $item ) {
		echo ( $first ? '' : ',' ) . wp_json_encode( $item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		$first = false;
	}
}

/* --------------------------------------------------------------------------
 * Export handler
 * ---------------------------------------------------------------------- */
add_action( 'admin_post_glow_fc_export', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'glow-fundraiser-core' ) );
	}
	check_admin_referer( 'glow_fc_export' );

	@set_time_limit( 0 );
	@ini_set( 'memory_limit', '1024M' );
	wp_raise_memory_limit( 'admin' );
	// Stop the object cache from ballooning while looping thousands of records.
	wp_suspend_cache_addition( true );

	$datasets = isset( $_POST['datasets'] ) && is_array( $_POST['datasets'] )
		? array_map( 'sanitize_key', wp_unslash( $_POST['datasets'] ) )
		: array( 'cpts' );
	$datasets = array_values( array_intersect( $datasets, array( 'cpts', 'orders', 'sellers' ) ) );
	if ( empty( $datasets ) ) {
		$datasets = array( 'cpts' );
	}
	$types  = isset( $_POST['types'] ) && is_array( $_POST['types'] )
		? array_map( 'sanitize_key', wp_unslash( $_POST['types'] ) )
		: glow_fc_ie_post_types();
	$format = ( isset( $_POST['format'] ) && 'csv' === $_POST['format'] ) ? 'csv' : 'json';

	$stamp = gmdate( 'Ymd-His' );
	nocache_headers();
	while ( ob_get_level() ) {
		ob_end_clean();
	}

	if ( 'json' === $format ) {
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="glow-fundraiser-' . $stamp . '.json"' );
		echo '{"plugin":"glow-fundraiser-core","version":2';
		echo ',"generated":' . wp_json_encode( gmdate( 'c' ) );
		echo ',"site_url":' . wp_json_encode( home_url() );
		if ( in_array( 'cpts', $datasets, true ) ) {
			echo ',"cpts":[';
			glow_fc_ie_stream_json_cpts( $types );
			echo ']';
		}
		if ( in_array( 'orders', $datasets, true ) ) {
			echo ',"orders":[';
			glow_fc_ie_stream_json_orders();
			echo ']';
		}
		if ( in_array( 'sellers', $datasets, true ) ) {
			echo ',"sellers":[';
			glow_fc_ie_stream_json_sellers();
			echo ']';
		}
		echo '}';
		exit;
	}

	// CSV path (builds arrays; object cache is suspended to keep this bounded).
	$data = array();
	if ( in_array( 'cpts', $datasets, true ) ) {
		$data['cpts'] = glow_fc_ie_collect_cpts( $types );
	}
	if ( in_array( 'orders', $datasets, true ) ) {
		$data['orders'] = glow_fc_ie_collect_orders();
	}
	if ( in_array( 'sellers', $datasets, true ) ) {
		$data['sellers'] = glow_fc_ie_collect_sellers();
	}

	// CSV: one file per dataset; zip when more than one.
	$files = array();
	foreach ( $data as $ds => $items ) {
		list( $cols, $rows ) = glow_fc_ie_dataset_to_csv( $ds, $items );
		$files[ $ds . '.csv' ] = glow_fc_ie_csv_string( $cols, $rows );
	}

	if ( count( $files ) === 1 ) {
		$name = array_key_first( $files );
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="glow-fundraiser-' . str_replace( '.csv', '', $name ) . '-' . $stamp . '.csv"' );
		echo reset( $files );
		exit;
	}

	if ( class_exists( 'ZipArchive' ) ) {
		$tmp = wp_tempnam( 'glow-fc-export' );
		$zip = new ZipArchive();
		$zip->open( $tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE );
		foreach ( $files as $fname => $content ) {
			$zip->addFromString( $fname, $content );
		}
		$zip->close();
		header( 'Content-Type: application/zip' );
		header( 'Content-Disposition: attachment; filename="glow-fundraiser-' . $stamp . '.zip"' );
		readfile( $tmp );
		@unlink( $tmp );
		exit;
	}

	// No ZipArchive: fall back to the first dataset only.
	$name = array_key_first( $files );
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="glow-fundraiser-' . str_replace( '.csv', '', $name ) . '-' . $stamp . '.csv"' );
	echo reset( $files );
	exit;
} );

/* --------------------------------------------------------------------------
 * Apply (import) one record of each dataset
 * ---------------------------------------------------------------------- */

function glow_fc_ie_apply_cpt( array $item, $update_existing ) {
	$type = isset( $item['post_type'] ) ? sanitize_key( $item['post_type'] ) : '';
	if ( ! in_array( $type, glow_fc_ie_post_types(), true ) ) {
		return new WP_Error( 'bad_type', 'unsupported post type' );
	}
	$postarr = array(
		'post_type'    => $type,
		'post_title'   => isset( $item['post_title'] ) ? wp_strip_all_tags( $item['post_title'] ) : '',
		'post_content' => (string) ( $item['post_content'] ?? '' ),
		'post_excerpt' => (string) ( $item['post_excerpt'] ?? '' ),
		'post_status'  => ! empty( $item['post_status'] ) ? sanitize_key( $item['post_status'] ) : 'publish',
		'post_name'    => isset( $item['post_name'] ) ? sanitize_title( $item['post_name'] ) : '',
		'menu_order'   => (int) ( $item['menu_order'] ?? 0 ),
	);
	if ( ! empty( $item['post_date'] ) ) {
		$postarr['post_date'] = sanitize_text_field( $item['post_date'] );
	}
	if ( $update_existing && ! empty( $item['original_id'] ) ) {
		$found = get_posts( array( 'post_type' => $type, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_glow_fc_orig_id', 'meta_value' => (string) (int) $item['original_id'] ) );
		if ( ! empty( $found ) ) {
			$postarr['ID'] = (int) $found[0];
		}
	}
	$pid = wp_insert_post( $postarr, true );
	if ( is_wp_error( $pid ) ) {
		return $pid;
	}
	update_post_meta( $pid, '_glow_fc_orig_id', (int) ( $item['original_id'] ?? 0 ) );
	foreach ( (array) ( $item['meta'] ?? array() ) as $key => $vals ) {
		if ( in_array( $key, array( '_glow_fc_orig_id', '_edit_lock', '_edit_last' ), true ) ) {
			continue;
		}
		delete_post_meta( $pid, $key );
		foreach ( (array) $vals as $val ) {
			add_post_meta( $pid, $key, $val );
		}
	}
	return $pid;
}

function glow_fc_ie_apply_order( array $data, $update_existing ) {
	if ( ! glow_fc_ie_wc_active() ) {
		return new WP_Error( 'no_wc', 'WooCommerce not active' );
	}
	$order = false;
	if ( $update_existing && ! empty( $data['original_id'] ) ) {
		$found = glow_fc_ie_find_order_ids_by_meta( '_glow_fc_orig_order_id', (int) $data['original_id'] );
		if ( ! empty( $found ) ) {
			$order = wc_get_order( $found[0] );
		}
	}
	if ( ! $order ) {
		$order = wc_create_order();
	}
	if ( is_wp_error( $order ) || ! $order ) {
		return new WP_Error( 'create', 'could not create order' );
	}
	if ( ! empty( $data['currency'] ) ) {
		$order->set_currency( sanitize_text_field( $data['currency'] ) );
	}
	if ( isset( $data['customer_id'] ) ) {
		$order->set_customer_id( (int) $data['customer_id'] );
	}
	if ( ! empty( $data['billing'] ) && is_array( $data['billing'] ) ) {
		$order->set_address( $data['billing'], 'billing' );
	}
	if ( ! empty( $data['shipping'] ) && is_array( $data['shipping'] ) ) {
		$order->set_address( $data['shipping'], 'shipping' );
	}
	// Add line items only when the order has none (avoid duplicating on update).
	if ( count( $order->get_items() ) === 0 ) {
		foreach ( (array) ( $data['items'] ?? array() ) as $it ) {
			$pid     = (int) ( $it['product_id'] ?? 0 );
			$product = $pid ? wc_get_product( $pid ) : false;
			if ( $product ) {
				$order->add_product( $product, (int) ( $it['qty'] ?? 1 ) );
			} else {
				$line = new WC_Order_Item_Product();
				$line->set_name( (string) ( $it['name'] ?? 'Item' ) );
				$line->set_quantity( (int) ( $it['qty'] ?? 1 ) );
				$line->set_subtotal( (float) ( $it['subtotal'] ?? 0 ) );
				$line->set_total( (float) ( $it['total'] ?? 0 ) );
				$order->add_item( $line );
			}
		}
	}
	foreach ( (array) ( $data['meta'] ?? array() ) as $k => $vals ) {
		if ( '_glow_fc_orig_order_id' === $k ) {
			continue;
		}
		$order->delete_meta_data( $k );
		foreach ( (array) $vals as $v ) {
			$order->add_meta_data( $k, $v, false );
		}
	}
	$order->update_meta_data( '_glow_fc_orig_order_id', (int) ( $data['original_id'] ?? 0 ) );
	if ( isset( $data['total'] ) && '' !== $data['total'] ) {
		$order->set_total( (float) $data['total'] );
	}
	if ( ! empty( $data['status'] ) ) {
		$order->set_status( sanitize_key( $data['status'] ) );
	}
	if ( ! empty( $data['date_created'] ) ) {
		$order->set_date_created( sanitize_text_field( $data['date_created'] ) );
	}
	$order->save();
	return $order->get_id();
}

function glow_fc_ie_apply_seller( array $data, $update_existing ) {
	$email = sanitize_email( $data['user_email'] ?? '' );
	$login = sanitize_user( $data['user_login'] ?? $email, true );
	if ( ! $email && ! $login ) {
		return new WP_Error( 'no_id', 'missing user_login/user_email' );
	}
	$user = $email ? get_user_by( 'email', $email ) : false;
	if ( ! $user && $login ) {
		$user = get_user_by( 'login', $login );
	}
	if ( $user && ! $update_existing ) {
		// Exists already; without "update" we still refresh meta/roles but never touch the password.
		$update_existing = true;
	}
	if ( ! $user ) {
		$uid = wp_insert_user( array(
			'user_login'    => $login,
			'user_email'    => $email,
			'first_name'    => sanitize_text_field( $data['first_name'] ?? '' ),
			'last_name'     => sanitize_text_field( $data['last_name'] ?? '' ),
			'display_name'  => sanitize_text_field( $data['display_name'] ?? $login ),
			'user_pass'     => wp_generate_password( 24 ),
			'role'          => ! empty( $data['roles'][0] ) ? sanitize_key( $data['roles'][0] ) : 'subscriber',
		) );
		if ( is_wp_error( $uid ) ) {
			return $uid;
		}
		$user = get_userdata( $uid );
	}
	$uid = $user->ID;
	if ( ! empty( $data['roles'] ) && is_array( $data['roles'] ) ) {
		$wpu = new WP_User( $uid );
		$wpu->set_role( '' );
		foreach ( $data['roles'] as $r ) {
			$wpu->add_role( sanitize_key( $r ) );
		}
	}
	foreach ( (array) ( $data['meta'] ?? array() ) as $k => $vals ) {
		if ( in_array( $k, array( 'session_tokens', 'user_pass', 'capabilities', 'wp_capabilities' ), true ) ) {
			continue;
		}
		delete_user_meta( $uid, $k );
		foreach ( (array) $vals as $v ) {
			add_user_meta( $uid, $k, $v );
		}
	}
	update_user_meta( $uid, '_glow_fc_orig_user_id', (int) ( $data['original_id'] ?? 0 ) );
	return $uid;
}

/* --------------------------------------------------------------------------
 * Import handler
 * ---------------------------------------------------------------------- */
add_action( 'admin_post_glow_fc_import', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'glow-fundraiser-core' ) );
	}
	check_admin_referer( 'glow_fc_import' );

	@set_time_limit( 0 );
	@ini_set( 'memory_limit', '1024M' );
	wp_raise_memory_limit( 'admin' );
	// Importing creates many posts/orders/users; stop the object cache from ballooning.
	wp_suspend_cache_addition( true );

	$update_existing = ! empty( $_POST['update_existing'] );
	$redirect        = admin_url( 'admin.php?page=glow-fc-data' );

	if ( empty( $_FILES['import_file']['tmp_name'] ) || ! is_uploaded_file( $_FILES['import_file']['tmp_name'] ) ) {
		wp_safe_redirect( add_query_arg( 'glow_fc_msg', 'nofile', $redirect ) );
		exit;
	}
	$tmp  = $_FILES['import_file']['tmp_name'];
	$name = isset( $_FILES['import_file']['name'] ) ? sanitize_file_name( $_FILES['import_file']['name'] ) : '';
	$ext  = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );
	if ( '' === $ext ) {
		$pf   = fopen( $tmp, 'r' );
		$peek = $pf ? ltrim( (string) fread( $pf, 64 ) ) : '';
		if ( $pf ) {
			fclose( $pf );
		}
		$ext = ( '' !== $peek && '{' === $peek[0] ) ? 'json' : 'csv';
	}

	$imp = 0;
	$fail = 0;
	$apply = function ( $dataset, $item ) use ( &$imp, &$fail, $update_existing ) {
		if ( ! is_array( $item ) ) {
			$fail++;
			return;
		}
		if ( 'cpts' === $dataset ) {
			$res = glow_fc_ie_apply_cpt( $item, $update_existing );
		} elseif ( 'orders' === $dataset ) {
			$res = glow_fc_ie_apply_order( $item, $update_existing );
		} else {
			$res = glow_fc_ie_apply_seller( $item, $update_existing );
		}
		is_wp_error( $res ) ? $fail++ : $imp++;
	};

	if ( 'json' === $ext ) {
		$raw  = file_get_contents( $tmp );
		$data = json_decode( (string) $raw, true );
		unset( $raw );
		if ( is_array( $data ) ) {
			// v2 envelope with named datasets, or a bare cpts array (v1).
			$has_sections = isset( $data['cpts'] ) || isset( $data['orders'] ) || isset( $data['sellers'] ) || isset( $data['items'] );
			if ( $has_sections ) {
				foreach ( array( 'cpts', 'orders', 'sellers' ) as $ds ) {
					foreach ( (array) ( $data[ $ds ] ?? array() ) as $item ) {
						$apply( $ds, $item );
					}
				}
				foreach ( (array) ( $data['items'] ?? array() ) as $item ) { // v1 back-compat
					$apply( 'cpts', $item );
				}
			} else {
				foreach ( $data as $item ) {
					$apply( 'cpts', $item );
				}
			}
		}
	} elseif ( 'csv' === $ext ) {
		$fh = fopen( $tmp, 'r' );
		if ( $fh ) {
			$head = fgetcsv( $fh );
			if ( is_array( $head ) ) {
				// Detect dataset from the header columns.
				if ( in_array( 'post_type', $head, true ) ) {
					$ds = 'cpts';
				} elseif ( in_array( 'user_email', $head, true ) ) {
					$ds = 'sellers';
				} else {
					$ds = 'orders';
				}
				while ( ( $row = fgetcsv( $fh ) ) !== false ) {
					if ( count( array_filter( $row, 'strlen' ) ) === 0 ) {
						continue;
					}
					$item = array( 'meta' => array() );
					foreach ( $head as $i => $col ) {
						$val = $row[ $i ] ?? '';
						if ( 0 === strpos( $col, 'meta:' ) ) {
							$mk      = substr( $col, 5 );
							$decoded = json_decode( $val, true );
							$item['meta'][ $mk ] = ( null !== $decoded && ( '[' === substr( $val, 0, 1 ) || '{' === substr( $val, 0, 1 ) ) ) ? $decoded : array( $val );
						} elseif ( in_array( $col, array( 'items', 'billing', 'shipping', 'roles' ), true ) ) {
							$dec          = json_decode( $val, true );
							$item[ $col ] = is_array( $dec ) ? $dec : array();
						} else {
							$item[ $col ] = $val;
						}
					}
					$apply( $ds, $item );
				}
			}
			fclose( $fh );
		}
	} else {
		wp_safe_redirect( add_query_arg( 'glow_fc_msg', 'badtype', $redirect ) );
		exit;
	}

	wp_safe_redirect( add_query_arg( array( 'glow_fc_msg' => 'done', 'imp' => $imp, 'fail' => $fail ), $redirect ) );
	exit;
} );

/* --------------------------------------------------------------------------
 * Admin page
 * ---------------------------------------------------------------------- */
function glow_fc_ie_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_GET['glow_fc_msg'] ) ) {
		$msg = sanitize_key( wp_unslash( $_GET['glow_fc_msg'] ) );
		if ( 'done' === $msg ) {
			echo '<div class="notice notice-success is-dismissible"><p>' .
				sprintf( esc_html__( 'Import complete: %1$d imported, %2$d skipped/failed.', 'glow-fundraiser-core' ), (int) ( $_GET['imp'] ?? 0 ), (int) ( $_GET['fail'] ?? 0 ) ) .
				'</p></div>';
		} elseif ( 'nofile' === $msg ) {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'No file was uploaded.', 'glow-fundraiser-core' ) . '</p></div>';
		} elseif ( 'badtype' === $msg ) {
			echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Unsupported file type. Upload .json, .csv (or a .zip of CSVs is produced on export only).', 'glow-fundraiser-core' ) . '</p></div>';
		}
	}

	$types = glow_fc_ie_post_types();
	$cpt_count = 0;
	foreach ( $types as $t ) {
		$c = wp_count_posts( $t );
		$cpt_count += $c ? array_sum( (array) $c ) : 0;
	}
	$order_count  = glow_fc_ie_wc_active() ? count( glow_fc_ie_find_order_ids_by_meta( GLOW_FC_ORDER_LINK_META ) ) : 0;
	$seller_count = count( get_users( array( 'meta_key' => GLOW_FC_SELLER_LINK_META, 'meta_compare' => 'EXISTS', 'fields' => 'ID' ) ) );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Fundraiser Data — Import / Export', 'glow-fundraiser-core' ); ?></h1>
		<p><?php esc_html_e( 'Export or import the full fundraiser dataset. JSON bundles everything for backup/restore; CSV gives one spreadsheet per dataset (zipped if several are selected).', 'glow-fundraiser-core' ); ?></p>

		<h2><?php esc_html_e( 'Export', 'glow-fundraiser-core' ); ?></h2>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="glow_fc_export" />
			<?php wp_nonce_field( 'glow_fc_export' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Datasets', 'glow-fundraiser-core' ); ?></th>
					<td>
						<label style="display:block;margin-bottom:6px;"><input type="checkbox" name="datasets[]" value="cpts" checked /> <?php esc_html_e( 'Post types (fundraiser, feedback, close out, payment history)', 'glow-fundraiser-core' ); ?> <span style="color:#777;">(<?php echo (int) $cpt_count; ?>)</span></label>
						<label style="display:block;margin-bottom:6px;"><input type="checkbox" name="datasets[]" value="orders" <?php echo glow_fc_ie_wc_active() ? '' : 'disabled'; ?> /> <?php esc_html_e( 'Orders (booking list, paid/unpaid, outstanding, send-check)', 'glow-fundraiser-core' ); ?> <span style="color:#777;">(<?php echo (int) $order_count; ?>)</span><?php echo glow_fc_ie_wc_active() ? '' : ' — ' . esc_html__( 'WooCommerce inactive', 'glow-fundraiser-core' ); ?></label>
						<label style="display:block;"><input type="checkbox" name="datasets[]" value="sellers" /> <?php esc_html_e( 'Sellers / representatives (users)', 'glow-fundraiser-core' ); ?> <span style="color:#777;">(<?php echo (int) $seller_count; ?>)</span></label>
						<p class="description"><?php esc_html_e( 'CPT sub-types to include (applies to the Post types dataset):', 'glow-fundraiser-core' ); ?><br />
							<?php foreach ( $types as $t ) : ?>
								<label style="display:inline-block;margin-right:14px;"><input type="checkbox" name="types[]" value="<?php echo esc_attr( $t ); ?>" checked /> <?php echo esc_html( $t ); ?></label>
							<?php endforeach; ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Format', 'glow-fundraiser-core' ); ?></th>
					<td>
						<label style="margin-right:16px;"><input type="radio" name="format" value="json" checked /> JSON <?php esc_html_e( '(all datasets, full fidelity)', 'glow-fundraiser-core' ); ?></label>
						<label><input type="radio" name="format" value="csv" /> CSV <?php esc_html_e( '(one file per dataset; zipped if several)', 'glow-fundraiser-core' ); ?></label>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Download export', 'glow-fundraiser-core' ) ); ?>
		</form>

		<hr />

		<h2><?php esc_html_e( 'Import', 'glow-fundraiser-core' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Upload a JSON export (any/all datasets) or a single dataset CSV. Note: order line items require the referenced products to exist on this site; imported users get a new random password (passwords are never exported).', 'glow-fundraiser-core' ); ?>
		</p>
		<form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="glow_fc_import" />
			<?php wp_nonce_field( 'glow_fc_import' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'File (.json or .csv)', 'glow-fundraiser-core' ); ?></th>
					<td><input type="file" name="import_file" accept=".json,.csv" required /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Update existing', 'glow-fundraiser-core' ); ?></th>
					<td><label><input type="checkbox" name="update_existing" value="1" /> <?php esc_html_e( 'Update records previously imported from the same source (match by original ID / email). Otherwise new records are created.', 'glow-fundraiser-core' ); ?></label></td>
				</tr>
			</table>
			<?php submit_button( __( 'Import file', 'glow-fundraiser-core' ), 'secondary' ); ?>
		</form>
	</div>
	<?php
}
