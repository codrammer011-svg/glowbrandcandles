<?php
/**
 * Template Name: Seller — Spring Summer Brochure Order
 */
defined( 'ABSPATH' ) || exit;

$glow_title = 'Spring Summer Brochure Order';

$glow_sections = array(

   array(
      'menu'     => 'Classic Candles',
      'heading'  => 'Classic Candles $15.00',
      'category' => 'classic-candles',
      'exclude'  => array('CC100','CC101','CC102','CC103','CC104','CC105','CC106','CC107','CC108'),
   ),

   array(
      'menu'     => 'Signature 2 Wick',
      'heading'  => 'Signature 2 Wick Candles $25.00',
      'category' => '2-wick-signature-candles',
      'exclude'  => array('SC270','SC271','SC272','SC273','SC274','SC275','SC276','SC277','SC278'),
   ),

   array(
      'menu'     => 'Large Wax Melts',
      'heading'  => 'Large Wax Melts $15.00',
      'category' => 'large-wax-melts-6-cubes',
      'exclude'  => array('LM440','LM441','LM442','LM443','LM444','LM445','LM446','LM447','LM448','SC18','SC19','CC3'),
   ),

   array(
      'menu'     => 'Room Sprays',
      'heading'  => 'Room Sprays $15.00',
      'category' => 'room-sprays-all-items',
      'exclude'  => array('RS610','RS611','RS612','RS613','RS614','RS615','RS616','RS617','RS618','SC18','SC19','CC3'),
   ),

   array(
      'menu'     => 'Essential Oils',
      'heading'  => 'Essential Oils $15.00',
      'category' => 'essential-oils',
      'exclude'  => array('EO74','SKU-S-EO118','SKU-S-EO124','SKU-S-EO126','SKU-S-EO115','SKU-W-EO115','SKU-W-EO86'),
   ),

   array(
      'menu'     => 'Boxed Candles',
      'heading'  => 'Boxed Candles $38.00',
      'category' => 'boxed-metallic',
      'exclude'  => array('SKU-S-BM93','SKU-S-BM94','SKU-S-BM95','SKU-S-BM96','SKU-S-BM95-1','SKU-S-WOS','SKU-S-BB','SKU-S-DFJ','SKU-S-B','SKU-S-WC','SKU-S-VIP','SKU-S-BBabe','SKU-S-CBB','SKU-S-PG','SKU-S-SB','SKU-S-NP','SKU-S-HDP','SKU-S-BS','SKU-S-WB','SKU-S-WB-2'),
   ),

);

$glow_catalog = 'spring-summer';

include plugin_dir_path( __FILE__ ) . 'glow-order-grid.php';
include plugin_dir_path( __FILE__ ) . 'glow-tour.php';