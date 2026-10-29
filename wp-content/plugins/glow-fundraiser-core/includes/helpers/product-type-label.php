<?php
/**
 * Product type under the product name (back office only).
 *
 * The old store put the product type in the title ("Fresh Linen Classic Candle").
 * Titles are now just the scent, so several products share one name. Admin
 * screens, PDFs, prints and reports show the type category on its own line
 * under the name; the storefront is left alone.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product-type category slugs, most specific first. Scent, season and
 * promotion categories (Fresh & Floral, Shop New, Catalogue...) are not types.
 */
function glow_product_type_slugs() {
	return apply_filters( 'glow_product_type_slugs', array(
		'let-it-snow-candle-16-oz',
		'whiskey-wildflower-candles',
		'celebrate-the-moments',
		'classic-candles',
		'2-wick-signature-candles',
		'luxe-gift-boxed-candle',
		'boxed-metallic',
		'luxury-boxed-grand-candle',
		'boxed-crystal',
		'16oz-round-classic',
		'designer-16oz-tins',
		'large-wax-melts-6-cubes',
		'melts',
		'room-sprays-5oz',
		'room-sprays-all-items',
		'essential-oils',
	) );
}

/**
 * Type category name for a product, order item or product ID; '' when the product has none.
 */
function glow_product_type_label( $product ) {
	static $cache = array();

	if ( $product instanceof WC_Order_Item_Product ) {
		$product_id = $product->get_product_id();
		// Orders from before the rename were saved as "Fresh Linen <br> Classic Candle": the type already prints on its own line.
		if ( stripos( $product->get_name(), '<br' ) !== false ) {
			return '';
		}
	} elseif ( $product instanceof WC_Product ) {
		$product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	} else {
		$product_id = (int) $product;
		$parent     = $product_id ? wp_get_post_parent_id( $product_id ) : 0;
		if ( $parent && get_post_type( $product_id ) === 'product_variation' ) {
			$product_id = $parent;
		}
	}
	if ( ! $product_id ) {
		return '';
	}
	if ( isset( $cache[ $product_id ] ) ) {
		return $cache[ $product_id ];
	}

	$label = '';
	$terms = get_the_terms( $product_id, 'product_cat' );
	if ( is_array( $terms ) ) {
		$by_slug = wp_list_pluck( $terms, 'name', 'slug' );
		foreach ( glow_product_type_slugs() as $slug ) {
			if ( isset( $by_slug[ $slug ] ) ) {
				$label = html_entity_decode( $by_slug[ $slug ], ENT_QUOTES, 'UTF-8' );
				break;
			}
		}
	}

	return $cache[ $product_id ] = $label;
}

/**
 * HTML line for the type, for templates that print item names.
 */
function glow_product_type_html( $product, $style = 'display:block;font-size:12px;color:#666;' ) {
	$label = glow_product_type_label( $product );
	return $label === '' ? '' : '<span class="glow-product-type" style="' . esc_attr( $style ) . '">' . esc_html( $label ) . '</span>';
}

/* Admin order screen. */
add_action( 'woocommerce_before_order_itemmeta', function ( $item_id, $item ) {
	if ( $item instanceof WC_Order_Item_Product ) {
		echo glow_product_type_html( $item, 'display:block;color:#50575e;margin-top:2px;' ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
}, 5, 2 );

/*
 * Order emails, WooCommerce's and the legacy custom ones (both fire woocommerce_order_item_meta_start).
 * The same hook runs on the customer's My Account / thank-you order table, which stays unchanged.
 */
add_action( 'woocommerce_order_details_before_order_table_items', function () {
	$GLOBALS['glow_in_storefront_order_table'] = true;
} );
add_action( 'woocommerce_order_details_after_order_table_items', function () {
	$GLOBALS['glow_in_storefront_order_table'] = false;
} );
add_action( 'woocommerce_order_item_meta_start', function ( $item_id, $item, $order = null, $plain_text = false ) {
	if ( ! empty( $GLOBALS['glow_in_storefront_order_table'] ) || ! $item instanceof WC_Order_Item_Product ) {
		return;
	}
	$label = glow_product_type_label( $item );
	if ( $label === '' ) {
		return;
	}
	if ( $plain_text ) {
		echo "\n" . esc_html( $label ); // phpcs:ignore WordPress.Security.EscapeOutput
	} else {
		echo '<br><small style="color:#666;">' . esc_html( $label ) . '</small>';
	}
}, 5, 4 );

/* PDF invoice and packing slip. */
add_action( 'wpo_wcpdf_before_item_meta', function ( $document_type, $item ) {
	$order_item = isset( $item['item'] ) ? $item['item'] : null;
	$source     = $order_item instanceof WC_Order_Item_Product ? $order_item : ( $item['product_id'] ?? 0 );
	$label      = glow_product_type_label( $source );
	if ( $label !== '' ) {
		echo '<p class="item-type" style="margin:0 0 4px;">' . esc_html( $label ) . '</p>';
	}
}, 5, 2 );
