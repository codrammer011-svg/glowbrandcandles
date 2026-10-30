<?php
/**
 * ShipStation item names: the product type on its own line under the scent,
 * like the invoices and prints ("Fresh Linen" / "Classic Candle").
 *
 * ShipStation shows names as plain text, so the old "<br>" in names from before
 * the rename showed up literally; it is sent as a real line break instead.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Item name for ShipStation, or null to leave the item as exported.
 */
function glow_shipstation_item_name( $item ) {
	if ( ! $item instanceof WC_Order_Item_Product ) {
		return null;
	}
	$name = (string) $item->get_name();
	if ( preg_match( '/<br\s*\/?>/i', $name ) ) {
		return trim( preg_replace( '/\s*<br\s*\/?>\s*/i', "\n", $name ) );
	}
	$type = function_exists( 'glow_product_type_label' ) ? glow_product_type_label( $item ) : '';
	return '' === $type ? null : $name . "\n" . $type;
}

/* REST API connection. */
add_filter( 'woocommerce_shipstation_fulfillment_item', function ( $fulfillment_item, $order, $item ) {
	$name = glow_shipstation_item_name( $item );
	if ( null !== $name && isset( $fulfillment_item['description'] ) ) {
		$fulfillment_item['description'] = $name;
	}
	return $fulfillment_item;
}, 10, 3 );

/* Legacy XML export. */
add_filter( 'woocommerce_shipstation_export_order_xml', function ( $order_xml ) {
	if ( ! $order_xml instanceof DOMElement ) {
		return $order_xml;
	}
	foreach ( $order_xml->getElementsByTagName( 'Item' ) as $item_xml ) {
		$id_node   = $item_xml->getElementsByTagName( 'LineItemID' )->item( 0 );
		$name_node = $item_xml->getElementsByTagName( 'Name' )->item( 0 );
		if ( ! $id_node || ! $name_node || $name_node->parentNode !== $item_xml ) {
			continue;
		}
		$name = glow_shipstation_item_name( WC_Order_Factory::get_order_item( (int) $id_node->textContent ) );
		if ( null === $name ) {
			continue;
		}
		while ( $name_node->firstChild ) {
			$name_node->removeChild( $name_node->firstChild );
		}
		$name_node->appendChild( $name_node->ownerDocument->createCDATASection( $name ) );
	}
	return $order_xml;
} );
