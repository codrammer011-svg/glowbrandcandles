<?php
/** Run with: php scripts/tests/shipment-order-remapping.php. No database required. */
$source = file_get_contents( dirname( __DIR__ ) . '/sync-recent-business.php' );
$start  = strpos( $source, 'function remapMeta(' );
$end    = false === $start ? false : strpos( $source, '$sourceIds=ids(', $start );
if ( false === $start || false === $end ) {
    throw new RuntimeException( 'Cannot isolate importer mapping function.' );
}

// Load only the pure mapping function; never execute the database importer.
$map      = array( 60360 => 63882, 60363 => 63884, 60804 => 60804 );
$products = array( 60360 => 70000 );
eval( substr( $source, $start, $end - $start ) );

$cases = array(
    array( 'ship_orders_ids', '60360,60363,60804', '63882,63884,60804' ),
    array( 'ship_orders_ids', '60360', '63882' ),
    array( 'ship_orders_ids', '', '' ),
    array( 'order_id', '60360', '63882' ),
    array( '_product_id', '60360', '70000' ),
    array( 'unrelated_metadata', '60360,60363', '60360,60363' ),
);
foreach ( $cases as list( $key, $value, $expected ) ) {
    if ( remapMeta( $value, $key ) !== $expected ) {
        throw new RuntimeException( 'Failed mapping check: ' . $key );
    }
}
echo count( $cases ) . " importer regression checks passed\n";
