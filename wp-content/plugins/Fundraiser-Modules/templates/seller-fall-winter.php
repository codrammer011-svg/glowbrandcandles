<?php
/**
 * Template Name: Seller — Fall-Winter Brochure Order
 */
defined( 'ABSPATH' ) || exit;

$glow_title = 'Fall-Winter Brochure Order';

$glow_sections = array(

   array(
      'menu'     => 'Classic Candles',
      'heading'  => 'Classic Candles $15.00',
      'category' => 'classic-candles',
      'allow'    => array('CC1','CC2','CC7','CC4','CC12','CC9','CC10','CC17','CC16',
                          'CC100','CC101','CC102','CC103','CC104','CC105','CC106','CC107'),
   ),

   array(
      'menu'     => 'Signature 2 Wick',
      'heading'  => 'Signature 2 Wick Candles $25.00',
      'category' => '2-wick-signature-candles',
      'allow'    => array('SC18','SC19','SC24','SC21','SC29','SC26','SC27','SC34','SC33',
                          'SC270','SC271','SC272','SC273','SC274','SC275','SC276','SC277'),
   ),

   array(
      'menu'     => 'Large Wax Melts',
      'heading'  => 'Large Wax Melts $15.00',
      'category' => 'large-wax-melts-6-cubes',
      'allow'    => array('LM35','LM36','LM41','LM38','LM46','LM43','LM44','LM51','LM50',
                          'LM440','LM441','LM442','LM443','LM444','LM445','LM446','LM447'),
   ),

   array(
      'menu'     => 'Room Sprays',
      'heading'  => 'Room Sprays $15.00',
      'category' => 'room-sprays-all-items',
      'allow'    => array('RS52','RS53','RS58','RS55','RS63','RS60','RS61','RS68','RS67',
                          'RS610','RS611','RS612','RS613','RS614','RS615','RS616','RS617'),
   ),

   array(
      'menu'     => 'Essential Oils',
      'heading'  => 'Essential Oils $15.00',
      'category' => 'essential-oils',
      'allow'    => array('EO69','EO70','EO71','EO72','EO73','EO74'),
   ),

   array(
      'menu'     => 'Let it Snow',
      'heading'  => 'Let it Snow $40.00',
      'category' => 'let-it-snow-candle-16-oz',
      /* allow list nahi — poori category */
   ),

   array(
      'menu'     => 'Celebrate The Moments',
      'heading'  => 'Celebrate the Moments $25.00',
      'category' => 'celebrate-the-moments',
      'allow'    => array('CTM217','CTM218','CTM219','CTM220','CTM221',
                          'CTM222','CTM223','CTM224','CTM225','CTM226'),
   ),

);

$glow_catalog = 'fall-winter';

include plugin_dir_path( __FILE__ ) . 'glow-order-grid.php';
include plugin_dir_path( __FILE__ ) . 'glow-tour.php';