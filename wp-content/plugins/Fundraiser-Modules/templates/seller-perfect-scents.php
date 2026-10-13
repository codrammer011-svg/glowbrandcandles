<?php
/**
 * Template Name: Seller — Perfect Scents for Every Season
 */
defined( 'ABSPATH' ) || exit;

$glow_title = 'Perfect Scents for Every Season Brochure';

$glow_sections = array(

   array(
      'menu'     => 'Signature 2 Wick',
      'heading'  => 'Signature 2 Wick Candles $25.00',
      'category' => '2-wick-signature-candles',
      'allow'    => array('SC200','SC201','SC202','SC203','SC204','SC205','SC206','SC207',
                          'SC208','SC209','SC210','SC211','SC212','SC213','SC214','SC215'),
   ),

   array(
      'menu'     => 'Celebrate The Moments',
      'heading'  => 'Celebrate the Moments $25.00',
      'category' => 'celebrate-the-moments',
      'allow'    => array('CTM217','CTM218','CTM219','CTM220','CTM221',
                          'CTM222','CTM223','CTM224','CTM225','CTM226'),
   ),

   array(
      'menu'     => 'Classic Candles',
      'heading'  => 'Classic Candles $15.00',
      'category' => 'classic-candles',
      'allow'    => array('CC227','CC228','CC229','CC230','CC231','CC232','CC233','CC234',
                          'CC235','CC236','CC237','CC238','CC239','CC240','CC241','CC242'),
   ),

   array(
      'menu'     => 'Large Wax Melts',
      'heading'  => 'Large Wax Melts $15.00',
      'category' => 'large-wax-melts-6-cubes',
      'allow'    => array('LM243','LM244','LM245','LM246','LM247','LM248','LM249','LM250',
                          'LM251','LM252','LM253','LM254','LM255','LM256','LM257','LM258'),
   ),

   array(
      'menu'     => 'Room Sprays',
      'heading'  => 'Room Sprays $15.00',
      'category' => 'room-sprays-all-items',
      'allow'    => array('RM259','RM260','RM261','RM262','RM263','RM264','RM265','RM266',
                          'RM267','RM268','RM269','RM270','RM271','RM272','RM273','RM274'),
   ),

   array(
      'menu'     => 'Essential Oils',
      'heading'  => 'Essential Oils $15.00',
      'category' => 'essential-oils',
      'allow'    => array('EO275','EO276','EO277','EO278','EO279'),
   ),

);

$glow_catalog = 'spring-summer';

include plugin_dir_path( __FILE__ ) . 'glow-order-grid.php';
include plugin_dir_path( __FILE__ ) . 'glow-tour.php';