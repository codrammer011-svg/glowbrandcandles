<?php
/**
 * GLOW Dashboard Fixes – chairperson brochure order pages, redesigned.
 * Applies to the Spring/Summer page (theme template-summercatalog.php) and both
 * Fall/Winter pages (template-new-wintercatalog.php, with and without ?new).
 * Pure CSS + JS layer on top of the existing markup, so the cart/quantity
 * scripts keep working unchanged.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function glow_bu_kind() {
	if ( ! is_page() ) {
		return '';
	}
	$tpl = basename( (string) get_page_template_slug() );
	if ( 'template-summercatalog.php' === $tpl ) {
		return 'summer';
	}
	if ( 'template-new-wintercatalog.php' === $tpl ) {
		return false !== strpos( (string) $_SERVER['REQUEST_URI'], 'new' ) ? 'perfect' : 'sparkle';
	}
	return '';
}


/* ------------------------------------------------------------------ */
/* Brochure product order (from the printed brochures)                  */
/* ------------------------------------------------------------------ */
function glow_bu_lists() {
	static $lists = null;
	if ( null !== $lists ) {
		return $lists;
	}
	$ss_scents = array( 'Eucalyptus Rain Mint', 'Fresh Linen', 'Lemon Lavender', 'Stress Reliever', 'Cool as a Cucumber', "Mom You're Amazing", 'Lavender and Chamomile', 'Orange Blossom', 'Mermaid Wishes & Starfish Kisses', 'Warm Vanilla Sugar', 'Strawberry Lemonade', 'Coconut Beach', 'Mango Tango', 'Golden Summer Glow', 'Berrylicious', 'Baked Apple Crisp', 'Mahogany Teakwood' );
	$ps_scents = array( 'Eucalyptus Rain Mint', 'Fresh Linen', 'Stress Reliever', 'Lavender Citrus Breeze', 'Ocean Mist', 'Coconut Beach', 'Mermaid Wishes & Starfish Kisses', 'Caribbean Escape', 'Berries & Woods', 'Warm Vanilla Sugar', 'Cotton Candy', 'Bonfire Butterscotch', 'Honeycrisp Apple', 'Caramel Pumpkin', 'Mahogany Teakwood', 'Walk In The Woods' );
	$seq = function ( $prefix, $from, $names ) { $o = array(); foreach ( $names as $i => $n ) { $o[] = array( $prefix . ( $from + $i ), $n ); } return $o; };
	$lists = array(
		'summer'  => array(
			'classic-candles'          => $seq( 'CC', 1, $ss_scents ),
			'2-wick-signature-candles' => $seq( 'SC', 18, $ss_scents ),
			'large-wax-melts-6-cubes'  => $seq( 'LM', 35, $ss_scents ),
			'room-sprays-5oz'          => $seq( 'RS', 52, $ss_scents ),
			'essential-oils'           => $seq( 'EO', 69, array( 'Cedarwood & Patchouli', 'Frankincense', 'Peppermint', 'Eucalyptus', 'Lavender' ) ),
			'boxed-metallic'           => $seq( 'BC', 74, array( 'Crisp Morning Air', 'Apple Blossom & Lavender', 'Watermelon Fizz', 'Berries & Wild Flowers' ) ),
		),
		'perfect' => array(
			'2-wick-signature-candles' => $seq( 'SC', 200, $ps_scents ),
			'celebrate-the-moments'    => $seq( 'CTM', 217, array( 'Best Teacher Ever', 'Book Loft', 'Family', 'Friends', 'Happy Birthday', 'Love', 'Relax & Rewind', 'Sweater Weather', 'Thank You', 'Vacation Vibes' ) ),
			'classic-candles'          => $seq( 'CC', 227, $ps_scents ),
			'large-wax-melts-6-cubes'  => $seq( 'LM', 243, $ps_scents ),
			'room-sprays-5oz'          => $seq( 'RM', 259, $ps_scents ),
			'essential-oils'           => $seq( 'EO', 275, array( 'Cedarwood & Patchouli', 'Frankincense', 'Peppermint', 'Eucalyptus', 'Lavender' ) ),
		),
		/* Season to Sparkle 2026 — order form pages 6-8 of the printed brochure */
		'sparkle' => array(
			'celebrate-the-moments'    => array( array( 'CTM224', 'Sweater Weather' ), array( 'CTM340', 'Pumpkin Patch' ), array( 'CTM341', 'Apple Orchard Days' ), array( 'CTM342', 'Fireside Flannel' ), array( 'CTM343', 'Crisp Autumn Air' ), array( 'CTM344', 'Tree Farm' ), array( 'CTM345', 'Sugar Plum Fairy' ), array( 'CTM346', 'Letters to Santa' ), array( 'CTM347', 'Elf!' ), array( 'CTM348', 'North Pole Latte' ), array( 'CTM349', 'Christmas Morning' ), array( 'CTM350', 'Snow Day' ), array( 'CTM351', 'Silent Night' ), array( 'CTM352', 'Peace on Earth' ), array( 'CTM353', 'Nutcracker Suite' ) ),
			'2-wick-signature-candles' => array( array( 'SC201', 'Fresh Linen' ), array( 'SC214', 'Mahogany Teakwood' ), array( 'SC310', 'Lavender Vanilla' ), array( 'SC200', 'Eucalyptus Rain Mint' ), array( 'SC209', 'Warm Vanilla Sugar' ), array( 'SC311', 'Baked Apple Crisp' ), array( 'SC213', 'Caramel Pumpkin' ), array( 'SC312', 'Cranberry Orange Spice' ), array( 'SC313', 'Falling Leaves' ), array( 'SC314', 'Christmas Tree' ), array( 'SC315', "Santa's Cookies" ), array( 'SC316', 'Candy Cane Lane' ), array( 'SC317', 'Holiday Home' ), array( 'SC318', 'Scent of Christmas' ), array( 'SC319', 'Winter Candy Apple' ) ),
			'classic-candles'          => array( array( 'CC228', 'Fresh Linen' ), array( 'CC241', 'Mahogany Teakwood' ), array( 'CC300', 'Lavender Vanilla' ), array( 'CC227', 'Eucalyptus Rain Mint' ), array( 'CC236', 'Warm Vanilla Sugar' ), array( 'CC301', 'Baked Apple Crisp' ), array( 'CC240', 'Caramel Pumpkin' ), array( 'CC302', 'Cranberry Orange Spice' ), array( 'CC303', 'Falling Leaves' ), array( 'CC304', 'Christmas Tree' ), array( 'CC305', "Santa's Cookies" ), array( 'CC306', 'Candy Cane Lane' ), array( 'CC307', 'Holiday Home' ), array( 'CC308', 'Scent of Christmas' ), array( 'CC309', 'Winter Candy Apple' ) ),
			'large-wax-melts-6-cubes'  => array( array( 'LM244', 'Fresh Linen' ), array( 'LM257', 'Mahogany Teakwood' ), array( 'LM320', 'Lavender Vanilla' ), array( 'LM243', 'Eucalyptus Rain Mint' ), array( 'LM252', 'Warm Vanilla Sugar' ), array( 'LM321', 'Baked Apple Crisp' ), array( 'LM256', 'Caramel Pumpkin' ), array( 'LM322', 'Cranberry Orange Spice' ), array( 'LM323', 'Falling Leaves' ), array( 'LM324', 'Christmas Tree' ), array( 'LM325', "Santa's Cookies" ), array( 'LM326', 'Candy Cane Lane' ), array( 'LM327', 'Holiday Home' ), array( 'LM328', 'Scent of Christmas' ), array( 'LM329', 'Winter Candy Apple' ) ),
			'room-sprays-5oz'          => array( array( 'RS260', 'Fresh Linen' ), array( 'RS273', 'Mahogany Teakwood' ), array( 'RS330', 'Lavender Vanilla' ), array( 'RS259', 'Eucalyptus Rain Mint' ), array( 'RS268', 'Warm Vanilla Sugar' ), array( 'RS331', 'Baked Apple Crisp' ), array( 'RS272', 'Caramel Pumpkin' ), array( 'RS332', 'Cranberry Orange Spice' ), array( 'RS333', 'Falling Leaves' ), array( 'RS334', 'Christmas Tree' ), array( 'RS335', "Santa's Cookies" ), array( 'RS336', 'Candy Cane Lane' ), array( 'RS337', 'Holiday Home' ), array( 'RS338', 'Scent of Christmas' ), array( 'RS339', 'Winter Candy Apple' ) ),
			'essential-oils'           => array( array( 'EO275', 'Cedarwood & Patchouli' ), array( 'EO276', 'Frankincense' ), array( 'EO277', 'Peppermint' ), array( 'EO278', 'Eucalyptus' ), array( 'EO279', 'Lavender' ), array( 'EO354', 'Holiday Cheer' ) ),
		),
	);
	return apply_filters( 'glow_brochure_lists', $lists );
}

function glow_bu_norm( $s ) {
	$s = html_entity_decode( (string) $s, ENT_QUOTES );
	$s = strtolower( $s );
	$s = str_replace( array( '&', ' and ' ), ' ', $s );
	$s = preg_replace( '/\b(signature 2 wick candles?|signature candle|2-wick candle|classic candle|scented melts?|large wax melts?|6 cubes|room spray|essential oil|candle|melts?|boxed metallic|gift)\b/', ' ', $s );
	$s = preg_replace( '/[^a-z0-9]+/', ' ', $s );
	return trim( preg_replace( '/\s+/', ' ', $s ) );
}

/** Products of a category in brochure order (by SKU, else by scent name). */
function glow_bu_resolve( $kind, $cat ) {
	$lists = glow_bu_lists();
	if ( empty( $lists[ $kind ][ $cat ] ) ) {
		return null;
	}
	$key = 'glow_bu_' . $kind . '_' . $cat;
	$ids = get_transient( $key );
	if ( ! is_array( $ids ) ) {
		$pool = wc_get_products( array( 'status' => 'publish', 'limit' => -1, 'category' => $cat, 'glow_bu_raw' => 1 ) );
		$byname = array();
		foreach ( $pool as $p ) {
			$byname[ glow_bu_norm( $p->get_name() ) ][] = $p;
		}
		$ids = array();
		foreach ( $lists[ $kind ][ $cat ] as $row ) {
			list( $sku, $name ) = $row;
			$pid = wc_get_product_id_by_sku( $sku );
			if ( $pid && 'publish' === get_post_status( $pid ) ) {
				$ids[] = (int) $pid;
				continue;
			}
			$cands = $byname[ glow_bu_norm( $name ) ] ?? array();
			if ( ! $cands ) {
				continue;
			}
			usort( $cands, function ( $a, $b ) {
				$da = substr_count( $a->get_sku(), '-' ); $db = substr_count( $b->get_sku(), '-' );
				return $da === $db ? $a->get_id() - $b->get_id() : $da - $db;
			} );
			$ids[] = $cands[0]->get_id();
		}
		set_transient( $key, $ids, 10 * MINUTE_IN_SECONDS );
	}
	$out = array();
	foreach ( $ids as $id ) {
		$p = wc_get_product( $id );
		if ( $p ) {
			$out[] = $p;
		}
	}
	return $out;
}

/** Which brochure is this page (chairperson templates + seller templates)? */
function glow_bu_page_kind() {
	$k = glow_bu_kind();
	if ( $k ) {
		return $k;
	}
	if ( ! is_page() ) {
		return '';
	}
	$raw = (string) get_page_template_slug();
	$tpl = basename( $raw );
	$map = array(
		'seller-spring-summer.php' => 'summer', 'seller-perfect-scents.php' => 'perfect', 'seller-fall-winter.php' => 'sparkle',
		'glow-spring-summer' => 'summer', 'glow-perfect-scents' => 'perfect', 'glow-fall-winter' => 'sparkle',
	);
	if ( isset( $map[ $tpl ] ) ) {
		return $map[ $tpl ];
	}
	if ( isset( $map[ $raw ] ) ) {
		return $map[ $raw ];
	}
	/* fall back on the page slug used by the seller dashboard config */
	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		$by_slug = array( 'seller-spring-summer' => 'summer', 'seller-perfect-scents' => 'perfect', 'seller-fall-winter' => 'sparkle' );
		if ( isset( $by_slug[ $slug ] ) ) {
			return $by_slug[ $slug ];
		}
	}
	return '';
}

add_filter( 'woocommerce_product_object_query', function ( $results, $args ) {
	if ( ! empty( $args['glow_bu_raw'] ) || empty( $args['category'] ) ) {
		return $results;
	}
	$kind = glow_bu_page_kind();
	if ( ! $kind ) {
		return $results;
	}
	$cat = is_array( $args['category'] ) ? reset( $args['category'] ) : $args['category'];
	if ( 'room-sprays-all-items' === $cat ) {
		$cat = 'room-sprays-5oz';
	}
	$r = glow_bu_resolve( $kind, $cat );
	return null === $r ? $results : $r;
}, 10, 2 );

/* Chairperson brochure tour: reuse the simple seller tour with chairperson selectors. */
function glow_bu_tour_steps( $steps ) {
	return array(
		array( 'icon' => 'bag', 'color' => 'pink', 'label' => 'Pick a category', 'selector' => '#menu', 'title' => 'Pick a category', 'text' => 'Jump straight to the section of the brochure you are entering — Classic Candles, Signature 2 Wick, Wax Melts and so on.' ),
		array( 'icon' => 'user', 'color' => 'orange', 'label' => 'Seller name', 'selector' => '.seller-cart-bar-wrap', 'title' => 'Choose the seller', 'text' => 'Every brochure order is saved under a seller\'s name, so you can hand the right candles to the right seller when they arrive.' ),
		array( 'icon' => 'candle', 'color' => 'purple', 'label' => 'Enter quantities', 'selector' => '#fullpage .section', 'title' => 'Enter the quantities', 'text' => 'Use the + and − buttons (or type) next to each product. Totals update as you go.' ),
		array( 'icon' => 'cart', 'color' => 'green', 'label' => 'Add to cart', 'selector' => '#box-glowscandal', 'title' => 'Add the order to your cart', 'text' => 'When the seller\'s form is entered, add it to your cart. Repeat for each seller, then check out once.' ),
	);
}

add_action( 'wp_head', function () {
	if ( ! glow_bu_kind() ) {
		return;
	}
	?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
	<style id="glow-brochure-ui">
	:root{--gb-pink:#ec0f63;--gb-ink:#181735;--gb-muted:#6f6c85;--gb-board:#f2f3f9;--gb-line:#e8e8f0;--gb-green:#17b98a}
	body.page-template-template-summercatalog,body.page-template-template-new-wintercatalog{background:var(--gb-board)}
	.glow-bu-head{font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;max-width:1160px;margin:26px auto 6px;padding:0 18px;color:var(--gb-ink)}
	.glow-bu-head a.glow-bu-back{display:inline-flex;gap:6px;font-size:14px;font-weight:600;color:#4a3fd0 !important;text-decoration:none !important;margin-bottom:12px}
	.glow-bu-head h1{margin:0 0 6px;font-size:28px;font-weight:800;letter-spacing:.01em;line-height:1.15}
	.glow-bu-head h1 span{display:inline-block;margin-left:10px;padding:4px 12px;border-radius:999px;background:#fff0f4;color:#9b1239;font-size:12.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;vertical-align:middle}
	.glow-bu-head p{margin:0;color:var(--gb-muted);font-size:15px;max-width:760px}
	.glow-bu-steps{display:flex;gap:10px;flex-wrap:wrap;margin:14px 0 0}
	.glow-bu-steps span{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid var(--gb-line);border-radius:999px;padding:7px 14px 7px 8px;font-size:13.5px;font-weight:600;color:var(--gb-ink)}
	.glow-bu-steps b{display:inline-flex;width:22px;height:22px;border-radius:50%;background:var(--gb-pink);color:#fff;font-size:12px;align-items:center;justify-content:center}
	/* --- sticky bar --- */
	.catalog-subnav-sticky{background:rgba(255,255,255,.96) !important;backdrop-filter:blur(8px);border-top:1px solid var(--gb-line) !important;border-bottom:1px solid var(--gb-line) !important;box-shadow:0 4px 14px rgba(22,20,50,.06) !important;padding:10px 0 !important;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif}
	.catalog-subnav-sticky .container{max-width:1160px !important;padding:0 18px !important;flex-wrap:nowrap !important}
	.catalog-subnav-sticky #menu{flex:1 1 auto;min-width:0;overflow-x:auto;flex-wrap:nowrap !important;scrollbar-width:none;padding:2px 0 !important}
	.catalog-subnav-sticky #menu::-webkit-scrollbar{display:none}
	.catalog-subnav-sticky #menu a.bookmark{white-space:nowrap}
	.catalog-subnav-sticky .seller-cart-bar-wrap{flex:0 0 auto}
	.catalog-subnav-sticky #menu{gap:6px !important}
	.catalog-subnav-sticky #menu a.bookmark{display:inline-block;padding:8px 14px !important;border-radius:999px !important;font-size:12.5px !important;font-weight:700 !important;letter-spacing:.04em;color:var(--gb-ink) !important;background:#f1f0f7;transition:background .15s,color .15s}
	.catalog-subnav-sticky #menu a.bookmark:hover{background:#e6e4f2}
	.catalog-subnav-sticky #menu a.bookmark.is-active{background:var(--gb-ink);color:#fff !important}
	.catalog-subnav-sticky .seller-cart-bar-wrap{display:flex !important;gap:8px !important;align-items:center !important}
	.catalog-subnav-sticky #nSeller{font:inherit !important;font-size:14px !important;padding:10px 14px !important;border:1.5px solid #d9d7e6 !important;border-radius:999px !important;background:#fff !important;min-width:200px !important;height:auto !important;box-shadow:none !important}
	.catalog-subnav-sticky .seller-cart-bar-wrap select{font:inherit !important;font-size:14px !important;padding:10px 36px 10px 14px !important;border:1.5px solid #d9d7e6 !important;border-radius:999px !important;background:#fff !important;min-width:190px !important;max-width:240px;height:auto !important;box-shadow:none !important;-webkit-appearance:none;appearance:none;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path d='M1 1l5 5 5-5' fill='none' stroke='%23181735' stroke-width='2'/></svg>") !important;background-repeat:no-repeat !important;background-position:right 14px center !important}
	#root,#root-2,.dknotus-tour,#positionsBtn{display:none !important}
	body .catalog-subnav-sticky .container{flex-wrap:wrap !important;row-gap:10px !important}
	body .catalog-subnav-sticky #menu{flex:1 1 auto !important;flex-wrap:wrap !important;overflow:visible !important;row-gap:6px !important}
	body .catalog-subnav-sticky .seller-cart-bar-wrap{margin-left:auto !important}
	#fullpage table.summerTable th:nth-child(3),#fullpage table.summerTable td:nth-child(3){width:120px !important}#fullpage table.summerTable th:nth-child(5),#fullpage table.summerTable td:nth-child(5){width:170px !important}#fullpage table.summerTable th:last-child,#fullpage table.summerTable td:last-child{width:100px !important}
	.catalog-subnav-sticky #nSeller:focus{outline:none;border-color:var(--gb-pink) !important;box-shadow:0 0 0 3px rgba(236,15,99,.12) !important}
	#btn-summer-add-order,.catalogue-dynamic-popup-btn{background:var(--gb-pink) !important;color:#fff !important;border:0 !important;border-radius:999px !important;padding:11px 22px !important;font-size:13px !important;font-weight:800 !important;letter-spacing:.05em;text-transform:uppercase;cursor:pointer;box-shadow:0 6px 16px rgba(236,15,99,.25) !important;line-height:1.2 !important;height:auto !important}
	#btn-summer-add-order:hover{filter:brightness(1.06)}
	/* --- category cards --- */
	#fullpage{max-width:1160px;margin:0 auto;padding:0 18px 120px;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif}
	#fullpage .section{margin:0 0 22px !important;background:#fff;border:1px solid var(--gb-line);border-radius:20px;box-shadow:0 2px 6px rgba(22,20,50,.05);padding:6px 22px 14px;height:auto !important}
	#fullpage .container.page-content,#fullpage .row,#fullpage .content{padding:0 !important;margin:0 !important;max-width:none !important;width:auto !important;float:none !important}
	#fullpage table.summerTable{width:100% !important;border-collapse:separate !important;border-spacing:0 !important;border:0 !important;font-family:inherit;display:table !important}
	#fullpage table.summerTable thead{display:table-header-group}
	#fullpage .glow-bu-h{margin:18px 0 10px !important;padding:0 !important;text-align:left !important;font-size:18px !important;font-weight:800 !important;letter-spacing:.01em;color:var(--gb-ink) !important;text-transform:none !important;line-height:1.3;font-family:inherit}
	#fullpage .glow-bu-h .glow-bu-price{margin-left:10px;padding:3px 11px;border-radius:999px;background:#eaf8f1;color:#0d6b50;font-size:12.5px;font-weight:800;vertical-align:middle}
	#fullpage .glow-bu-h .glow-bu-count{margin-left:8px;color:var(--gb-muted);font-size:13px;font-weight:600}
	#fullpage table.summerTable th{padding:8px 12px !important;text-align:left !important;font-size:11.5px !important;font-weight:700 !important;letter-spacing:.05em;text-transform:uppercase;color:#8d8aa0 !important;background:#faf9fc !important;border:0 !important;border-top:1px solid var(--gb-line) !important;border-bottom:1px solid var(--gb-line) !important;width:auto !important}
	#fullpage table.summerTable th:first-child{border-radius:10px 0 0 0}#fullpage table.summerTable th:last-child{border-radius:0 10px 0 0}
	#fullpage table.summerTable td{padding:9px 12px !important;border:0 !important;border-bottom:1px solid #f0eff4 !important;vertical-align:middle !important;background:#fff !important;font-size:14.5px;color:var(--gb-ink);width:auto !important;height:auto !important}
	#fullpage table.summerTable tr.odd td,#fullpage table.summerTable tr.even td{background:#fff !important}
	#fullpage table.summerTable tbody tr:hover td{background:#faf9fc !important}
	#fullpage table.summerTable tbody tr.glow-bu-picked td{background:#fff5f8 !important}
	#fullpage table.summerTable td.sorting_1,#fullpage table.summerTable th:first-child{width:72px !important}
	#fullpage table.summerTable img.card-image{width:54px !important;height:54px !important;object-fit:contain;border-radius:12px;background:#f7f7fb;padding:4px;display:block}
	#fullpage table.summerTable .item_no{display:inline-block;padding:3px 9px;border-radius:999px;background:#f1eff6;color:#5d5a75;font-size:12px;font-weight:700;letter-spacing:.03em}
	#fullpage table.summerTable .summer_cart{display:none !important}
	#fullpage table.summerTable td:nth-child(2){font-weight:600}
	#fullpage table.summerTable td:last-child{font-weight:700;white-space:nowrap;text-align:right}
	#fullpage table.summerTable th:last-child{text-align:right !important}
	/* qty stepper */
	.glow-bu-qty{display:inline-flex;align-items:center;border:1.5px solid #d9d7e6;border-radius:999px;background:#fff;overflow:hidden}
	.glow-bu-qty button{width:34px;height:36px;border:0;background:#fff;color:var(--gb-ink);font-size:18px;font-weight:700;cursor:pointer;line-height:1;padding:0}
	.glow-bu-qty button:hover{background:#f1eff6}
	.glow-bu-qty input.qty{width:54px !important;height:36px !important;border:0 !important;border-left:1px solid #ecebf2 !important;border-right:1px solid #ecebf2 !important;border-radius:0 !important;text-align:center;font:inherit;font-size:15px !important;font-weight:700;padding:0 !important;margin:0 !important;background:#fff !important;box-shadow:none !important;-moz-appearance:textfield}
	.glow-bu-qty input.qty::-webkit-outer-spin-button,.glow-bu-qty input.qty::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
	.glow-bu-qty input.qty:focus{outline:none;background:#fff5f8 !important}
	/* floating totals bar */
	#box-glowscandal{position:fixed !important;left:50% !important;right:auto !important;bottom:14px !important;top:auto !important;transform:translateX(-50%);width:min(1120px,calc(100% - 28px)) !important;display:flex !important;align-items:center;gap:18px;background:#fff !important;color:var(--gb-ink) !important;border:1px solid var(--gb-line);border-radius:18px !important;box-shadow:0 14px 38px rgba(22,20,50,.18) !important;padding:12px 18px !important;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;z-index:9000;margin:0 !important;height:auto !important}
	#box-glowscandal .product-numbers{margin:0 !important;padding:0 !important;display:none}
	#box-glowscandal #cst_summer_qty{display:none}
	.glow-bu-tot-label{font-size:11.5px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:#8d8aa0;white-space:nowrap}
	.glow-bu-tot-row{display:flex;gap:12px;align-items:baseline;flex:1}
	.glow-bu-tot-row b{font-size:22px;font-weight:800}
	.glow-bu-tot-row small{font-size:13px;color:var(--gb-muted);font-weight:600}
	.glow-bu-tot-hint{font-size:12.5px;color:var(--gb-muted);white-space:nowrap}
	.glow-bu-tot-btn{display:inline-flex;justify-content:center;padding:11px 22px;border-radius:999px;background:var(--gb-pink);color:#fff;font-weight:800;font-size:13px;letter-spacing:.05em;text-transform:uppercase;border:0;cursor:pointer;white-space:nowrap}
	.glow-bu-tot-btn:disabled{background:#d9d7e6;color:#fff;cursor:not-allowed}
	/* legacy bits we do not want */
	#fullpage p[id][style*="padding-top"],#fullpage p[id][style*="margin-top"]{padding-top:0 !important;margin-top:0 !important;height:0;overflow:hidden}
	#fullpage .dataTables_wrapper .dataTables_length,#fullpage .dataTables_wrapper .dataTables_filter,#fullpage .dataTables_wrapper .dataTables_info,#fullpage .dataTables_wrapper .dataTables_paginate{display:none !important}
	@media(max-width:760px){
		.glow-bu-head h1{font-size:22px}.glow-bu-head h1 span{display:block;width:max-content;margin:8px 0 0}
		.catalog-subnav-sticky #menu{overflow-x:auto;flex-wrap:nowrap !important;-webkit-overflow-scrolling:touch;max-width:100%;padding-bottom:4px !important}
		.catalog-subnav-sticky #menu a.bookmark{white-space:nowrap}
		.catalog-subnav-sticky .container{flex-wrap:wrap !important}.catalog-subnav-sticky .seller-cart-bar-wrap{width:100%;flex:1 1 100%}.catalog-subnav-sticky #nSeller{flex:1;min-width:0 !important}
		#fullpage{padding:0 10px 160px}#fullpage .section{padding:4px 12px 10px;border-radius:16px}
		#fullpage table.summerTable thead tr,#fullpage table.summerTable tr:has(th){display:none}
		#fullpage table.summerTable tbody tr:not(:has(th)){display:grid;grid-template-columns:60px 1fr auto;grid-template-areas:"img name price" "img sku qty";align-items:center;column-gap:10px;padding:10px 0;border-bottom:1px solid #f0eff4}
		#fullpage table.summerTable td{display:block;border:0 !important;padding:2px 0 !important}
		#fullpage table.summerTable td.sorting_1{grid-area:img}#fullpage table.summerTable td:nth-child(2){grid-area:name}#fullpage table.summerTable td:nth-child(3){grid-area:sku}#fullpage table.summerTable td:nth-child(5){grid-area:qty;justify-self:end}#fullpage table.summerTable td:last-child{grid-area:price;text-align:right}
		#box-glowscandal{flex-wrap:wrap;gap:8px 14px;padding:10px 14px !important}.glow-bu-tot-hint{display:none}.glow-bu-tot-btn{flex:1}
	}
	@media print{.catalog-subnav-sticky,#box-glowscandal,.glow-bu-steps,header,footer,#wpadminbar{display:none !important}#fullpage .section{box-shadow:none;border:0;page-break-inside:avoid}}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	$kind = glow_bu_kind();
	if ( ! $kind ) {
		return;
	}
	$titles = array(
		'summer'  => array( 'Spring / Summer Brochure Order', 'Spring / Summer' ),
		'perfect' => array( 'Perfect Scents for Every Season', 'Fall / Winter' ),
		'sparkle' => array( 'Season to Sparkle Brochure Order', 'Fall / Winter' ),
	);
	$t    = $titles[ $kind ];
	$u    = wp_get_current_user();
	$name = trim( get_user_meta( $u->ID, 'first_name', true ) );
	$back = home_url( '/fundraiser-dashboard/' );
	?>
	<script id="glow-brochure-ui-js">
	(function(){
		/* kill the old React/dknotus brochure tour for good */
		function glowKillOldTour(){ document.querySelectorAll('#root,#root-2,.dknotus-tour,#positionsBtn').forEach(function(e){ e.remove(); }); }
		glowKillOldTour();
		try { new MutationObserver(glowKillOldTour).observe(document.body, {childList:true}); } catch(e){}
		var sticky = document.querySelector('.catalog-subnav-sticky');
		var fullpage = document.getElementById('fullpage');
		if (!sticky || !fullpage) return;

		<?php if ( 'sparkle' === $kind ) : ?>
		/* Season to Sparkle: category order as on the printed order form */
		(function(){
			var order = ['celebrate_the_moments','signature_wick','classic_candles','large_wax_melts','room-spray','essential-oils','let-it-snow'];
			var menu = sticky.querySelector('#menu');
			var tail = fullpage.querySelector('#back-top');
			order.forEach(function(id){
				var a = fullpage.querySelector('#' + CSS.escape(id));
				while (a && a.parentNode !== fullpage) a = a.parentNode;
				if (!a) return;
				var s = a.nextElementSibling;
				while (s && !s.classList.contains('section')) s = s.nextElementSibling;
				var ref = tail && tail.parentNode === fullpage ? tail : null;
				fullpage.insertBefore(a, ref);
				if (s) fullpage.insertBefore(s, ref);
				if (menu) { var l = menu.querySelector('a[href="#' + id + '"]'); if (l) menu.appendChild(l.closest('li') || l); }
			});
		})();
		<?php endif; ?>

		/* header */
		var head = document.createElement('div');
		head.className = 'glow-bu-head';
		head.innerHTML = '<a class="glow-bu-back" href="<?php echo esc_url( $back ); ?>">&larr; Back to my dashboard</a>'
			+ '<h1><?php echo esc_js( $t[0] ); ?><span><?php echo esc_js( $t[1] ); ?></span></h1>'
			+ '<p><?php echo esc_js( $name ? 'Hi ' . $name . ', e' : 'E' ); ?>nter the quantities from your paper order forms below. Totals update as you type; nothing is charged until you check out.</p>'
			+ '<div class="glow-bu-steps"><span><b>1</b>Type the seller\'s name</span><span><b>2</b>Enter quantities</span><span><b>3</b>Add order to cart &amp; check out</span></div>';
		sticky.parentNode.insertBefore(head, sticky);

		/* headings: price badge + item count */
		fullpage.querySelectorAll('table.summerTable').forEach(function(tbl){
			var p = tbl.querySelector('thead p');
			if (!p) { var prev = tbl.previousElementSibling; while (prev && !(prev.tagName === 'P' && /\$/.test(prev.textContent))) prev = prev.previousElementSibling; p = prev || (tbl.parentElement ? tbl.parentElement.querySelector(':scope > p') : null); }
			if (!p) return;
			p.classList.add('glow-bu-h'); p.removeAttribute('align'); p.removeAttribute('style');
			var txt = p.textContent.trim();
			var m = txt.match(/^(.*?)\s*(\$\s?[\d.,]+)\s*$/);
			var n = tbl.querySelectorAll('input.qty').length;
			if (m) { p.innerHTML = m[1].toLowerCase().replace(/\b\w/g, function(c){ return c.toUpperCase(); }) + '<span class="glow-bu-price">' + m[2].replace(/\s/g,'') + ' each</span>'; }
			p.insertAdjacentHTML('beforeend', '<span class="glow-bu-count">' + n + ' item' + (n === 1 ? '' : 's') + '</span>');
		});

		/* quantity steppers */
		function fire(inp){ ['input','change','keyup'].forEach(function(ev){ inp.dispatchEvent(new Event(ev, { bubbles: true })); }); }
		fullpage.querySelectorAll('input.qty').forEach(function(inp){
			if (inp.closest('.glow-bu-qty')) return;
			var wrap = document.createElement('span'); wrap.className = 'glow-bu-qty';
			var minus = document.createElement('button'); minus.type = 'button'; minus.textContent = '−'; minus.setAttribute('aria-label','Less');
			var plus = document.createElement('button'); plus.type = 'button'; plus.textContent = '+'; plus.setAttribute('aria-label','More');
			inp.parentNode.insertBefore(wrap, inp); wrap.appendChild(minus); wrap.appendChild(inp); wrap.appendChild(plus);
			inp.placeholder = '0';
			minus.addEventListener('click', function(){ var v = parseInt(inp.value, 10) || 0; inp.value = v > 1 ? v - 1 : ''; fire(inp); });
			plus.addEventListener('click', function(){ var v = parseInt(inp.value, 10) || 0; inp.value = v + 1; fire(inp); });
		});

		/* row highlight + floating totals */
		var box = document.getElementById('box-glowscandal');
		var mainBtn = document.getElementById('btn-summer-add-order');
		if (box) {
			box.insertAdjacentHTML('beforeend', '<div class="glow-bu-tot-label">Your brochure order</div><div class="glow-bu-tot-row"><small id="glow-bu-items">0 items</small><b id="glow-bu-total">$0.00</b></div>'
				+ (mainBtn ? '<div class="glow-bu-tot-hint">Enter quantities to get started</div><button type="button" class="glow-bu-tot-btn" id="glow-bu-add" disabled>Add order to cart</button>' : ''));
			var add = document.getElementById('glow-bu-add');
			if (add) add.addEventListener('click', function(){ var s = document.getElementById('nSeller') || document.querySelector('.seller-cart-bar-wrap select'); if (s && !s.value.trim()) { s.focus(); s.scrollIntoView({behavior:'smooth', block:'center'}); return; } mainBtn.click(); });
		}
		function refresh(){
			var items = 0, total = 0;
			fullpage.querySelectorAll('table.summerTable tbody tr').forEach(function(tr){
				var q = parseInt((tr.querySelector('input.qty') || {}).value, 10) || 0;
				var chk = tr.querySelector('input.add'); var price = chk ? parseFloat(chk.getAttribute('data-price')) || 0 : 0;
				tr.classList.toggle('glow-bu-picked', q > 0);
				items += q; total += q * price;
			});
			var i = document.getElementById('glow-bu-items'), tt = document.getElementById('glow-bu-total'), add = document.getElementById('glow-bu-add'), hint = document.querySelector('.glow-bu-tot-hint');
			if (i) i.textContent = items + (items === 1 ? ' item' : ' items');
			if (tt) tt.textContent = '$' + total.toFixed(2);
			var seller = document.getElementById('nSeller') || document.querySelector('.seller-cart-bar-wrap select');
			if (add) { add.disabled = !(items > 0); }
			if (hint) { hint.textContent = items === 0 ? 'Enter quantities to get started' : (seller && !seller.value.trim() ? 'Fill in the seller name at the top first' : 'Ready when you are'); }
		}
		document.addEventListener('input', refresh, true); document.addEventListener('change', refresh, true); document.addEventListener('keyup', refresh, true);
		refresh();

		/* chairperson wording in the shared welcome modal */
		var wm = document.getElementById('glowWelcome');
		if (wm) { var w = document.createTreeWalker(wm, NodeFilter.SHOW_TEXT); var tn = []; while (w.nextNode()) tn.push(w.currentNode); tn.forEach(function(n){ n.nodeValue = n.nodeValue.replace(/Glow Seller!?/i, 'Chairperson!').replace(/\b5 simple steps\b/i, '4 simple steps').replace(/your chairperson knows/i, 'you know'); }); }
		/* active category in the sticky bar */
		var links = Array.prototype.slice.call(sticky.querySelectorAll('#menu a.bookmark'));
		var sections = Array.prototype.slice.call(fullpage.querySelectorAll('.section'));
		function setActive(){
			var y = window.scrollY + sticky.offsetHeight + 120, idx = 0;
			sections.forEach(function(s, i){ if (s.offsetTop <= y) idx = i; });
			links.forEach(function(a, i){ a.classList.toggle('is-active', i === idx); });
		}
		links.forEach(function(a, i){ a.addEventListener('click', function(e){ var s = sections[i]; if (!s) return; e.preventDefault(); window.scrollTo({ top: s.offsetTop - sticky.offsetHeight - 8, behavior: 'smooth' }); }); });
		window.addEventListener('scroll', setActive, { passive: true }); setActive();
	})();
	</script>
	<?php
}, 30 );
